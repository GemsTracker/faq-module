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
     * @var array
     */
    protected $exampleData = [
        'gfi_title' => 'Example question',
        'gfi_body' => '[b]Lorem ipsum dolor sit[/b] amet, [i]consectetur adipiscing[/i] elit. Curabitur efficitur finibus mauris tempor porttitor. Ut mattis neque sit amet orci placerat ornare. Suspendisse potenti. Ut at libero malesuada, facilisis ipsum at, blandit nisi. Sed elementum tellus id justo imperdiet, vel pharetra mi ultrices. Pellentesque non nunc varius, aliquam lorem sed, tincidunt velit. Nulla non enim non nulla sollicitudin convallis. Curabitur vestibulum ultricies tellus. Fusce faucibus efficitur lacus, vel efficitur ipsum consectetur sed.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. [b]Maecenas sit amet nisi dapibus[/b], lobortis nulla in, dignissim arcu. Pellentesque vel bibendum mauris. Suspendisse cursus, ipsum nec egestas elementum, nisi odio gravida ex, nec fringilla nulla leo eu augue. Fusce vel convallis diam. In lacinia massa sit amet ante consequat venenatis. Aenean semper elit vel pulvinar vulputate. Morbi ac turpis condimentum, gravida augue non, feugiat tellus. Nunc eu est non nibh tincidunt mattis vel sed dolor. In hac habitasse platea dictumst. In metus erat, fermentum ac vulputate ac, facilisis eu ligula.

Fusce ultricies nibh eu leo consectetur accumsan. Ut lobortis volutpat sapien non tincidunt. Ut sit amet felis vel lorem malesuada finibus ut eu ipsum. Proin iaculis, libero vehicula varius auctor, magna ligula faucibus nulla, id mattis odio lacus vitae augue. Nulla facilisi. Donec luctus suscipit erat et bibendum. Nunc tincidunt justo quis quam fermentum, vitae ornare massa efficitur. Pellentesque eleifend vitae erat vitae placerat. Maecenas interdum libero vestibulum mollis porta. Maecenas porta, turpis vitae malesuada convallis, dolor mi vehicula nisi, at scelerisque odio turpis nec est. Fusce eleifend elit ut dui rutrum aliquet.',
        'gfg_active' => 1,
    ];

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

        $div = \MUtil_Html::create('div', array('class' => 'mailpreview'));
        $div->bbcode($bbcode);

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
    
    /**
     * Optional additional instructions
     *
     * @return \MUtil_Html_HtmlInterface Something that can be rendered
     */
    public function getInstructions()
    {
        $seq = $this->getHtmlSequence();
        $seq->pInfo()->bbcode($this->_('Use the [code]Source[/code] button to view the underlying codes.'));
        
        $p = $seq->pInfo($this->_('Tou can find more instructions on the PHP BB Code site:'));
        $p->a('https://www.phpbb.com/community/help/bbcode', $this->_('BBCode guide'));
        return $seq;
    }
}