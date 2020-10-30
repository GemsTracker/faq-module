<?php

/**
 *
 * @package    JointCompassion
 * @subpackage Default
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Maasstad Ziekenhuis and MagnaFacta B.V.
 * @license    No free license, do not copy
 */

/**
 *
 * @package    JointCompassion
 * @subpackage Default
 * @copyright  Copyright (c) 2020, Maasstad Ziekenhuis and MagnaFacta B.V.
 * @license    No free license, do not copy
 * @since      Class available since version 1.8.8 14-May-2020 18:52:33
 */
class FaqSetupController extends \Gems_Controller_ModelSnippetActionAbstract
{
    /**
     * The snippets used for the autofilter action.
     *
     * @var mixed String or array of snippets name
     */
    protected $autofilterParameters = array(
        'extraSort'   => array('gfi_id_order' => SORT_ASC),
        );

    /**
     * The parameters used for the deactivate action.
     *
     * When the value is a function name of that object, then that functions is executed
     * with the array key as single parameter and the return value is set as the used value
     * - unless the key is an integer in which case the code is executed but the return value
     * is not stored.
     *
     * @var array Mixed key => value array for snippet initialization
     */
    protected $deactivateParameters = [
        'saveData' => ['gfi_active' => 0],
        ];

    /**
     * @var \Gems_Menu
     */
    public $menu;

    /**
     * @var \Gems_Project_ProjectSettings
     */
    public $project;
    
    /**
     * @var \Gems_Util
     */
    public $util;

    /**
     * The parameters used for the reactivate action.
     *
     * When the value is a function name of that object, then that functions is executed
     * with the array key as single parameter and the return value is set as the used value
     * - unless the key is an integer in which case the code is executed but the return value
     * is not stored.
     *
     * @var array Mixed key => value array for snippet initialization
     */
    protected $reactivateParameters = [
        'saveData' => ['gfi_active' => 1],
        ];

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
     * Creates a model for getModel(). Called only for each new $action.
     *
     * The parameters allow you to easily adapt the model to the current action. The $detailed
     * parameter was added, because the most common use of action is a split between detailed
     * and summarized actions.
     *
     * @param boolean $detailed True when the current action is not in $summarizedActions.
     * @param string $action The current action.
     * @return \MUtil_Model_ModelAbstract
     */
    protected function createModel($detailed, $action)
    {
        $model   = new \MUtil_Model_TableModel('gemsfaq__items');

        $pages = [
            'FAQ' => $this->_('Support and help'),
            'Info' => $this->_('Surveys information'),
            ];
        $model->set('gfi_page', 'label', $this->_('Page'),
            'multiOptions', $pages);
        $model->set('gfi_group', 'label', $this->_('FAQ Group'), 'size', 40);

        $model->set('gfi__iso_lang',    'label', $this->_('Language'),
                    'multiOptions', $this->util->getLocalized()->getLanguages(),
                    'tab', $this->_('Settings'), 'default', $this->project->getLocaleDefault());
        
        $model->set('gfi_id_order',          'label', $this->_('Order'),
                'default', 10,
                'required', true,
                'validators[uni]', $model->createUniqueValidator(array('gfi_id_order'))
                );

        $model->set('gfi_question', 'label', $this->_('Question'),
            'size', 60,
            'required', true);

        if ($detailed) {
            $model->set('gfi_body', 'label', $this->_('Answer'),
                        'cols', 60,
                        'decorators', array('CKEditor'),
                        'elementClass', 'textarea',
                        'formatFunction', array($this, 'bbToHtml'),
                        'required', true,
                        'rows', 8
                        );
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
        }

        $model->set('gfi_active', 'label', $this->_('Active'),
                'elementClass', 'Checkbox',
                'multiOptions', $this->util->getTranslated()->getYesNo()
                );
        // $model->setDeleteValues('gfi_active', 0);
        $model->addColumn(new \Zend_Db_Expr("CASE WHEN gfi_active = 1 THEN '' ELSE 'deleted' END"), 'row_class');

        \Gems_Model::setChangeFieldsByPrefix($model, 'gfi');

        return $model;
    }

    /**
     * Helper function to allow generalized statements about the items in the model.
     *
     * @param int $count
     * @return $string
     */
    public function getTopic($count = 1)
    {
        return $this->plural('question', 'questions', $count);
    }
}
