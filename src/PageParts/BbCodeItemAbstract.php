<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
abstract class BbCodeItemAbstract extends ItemAbstract
{
    /**
     * @var \Zend_View
     */
    protected $view;

    /**
     * @var bool Check whether the view has been set
     */
    private static $viewIsSet = false;

    /**
     * Called after the check that all required registry values
     * have been set correctly has run.
     *
     * This function is no needed if the classes are setup correctly
     *
     * @return void
     */
    public function afterRegistry()
    {
        parent::afterRegistry();

        if (self::$viewIsSet) {
            return;
        }
        
        $config = array(
            'extraPlugins' => 'bbcode,availablefields',
            'toolbar' => array(
                array('Source','-','Undo','Redo'),
                array('Find','Replace','-','SelectAll','RemoveFormat'),
                array('Link', 'Unlink', 'Image', 'SpecialChar'),
                '/',
                array('Bold', 'Italic','Underline'),
                array('NumberedList','BulletedList','-','Blockquote'),
                array('Maximize'),
                array('availablefields')
            )
        );
        // $config['availablefields'] = ['tokenLost' => '/ask/lost'];

        // $config['availablefieldsLabel'] = $this->_('Fields');
        $this->view->inlineScript()->prependScript("
                CKEditorConfig = ".\Zend_Json::encode($config).";
                ");
        
        self::$viewIsSet = true;
    }

    /**
     * Display a template body
     *
     * @param string $bbcode
     * @return \MUtil_Html_HtmlElement
     */
    public function bbToHtml($bbcode)
    {
        if (empty($bbcode)) {
            $em = \MUtil_Html::create('em');
            $em->raw($this->_('&laquo;empty&raquo;'));

            return $em;
        }

        $text = \MUtil_Markup::render($bbcode, 'Bbcode', 'Html');

        $div = \MUtil_Html::create('div', array('class' => 'mailpreview'));
        $div->raw($text);

        return $div;
    }

    /**
     * @inheritDoc
     */
    public function getBodySettings()
    {
        return [
            'label'          => $this->_('Answer'),
            'cols'           => 60,
            'decorators'     => ['CKEditor'],
            'elementClass'   => 'Textarea',
            'formatFunction' => [$this, 'bbToHtml'],
            'required'       => true,
            'rows'           => 8,
            ];
    }
}