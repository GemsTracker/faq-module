<?php

/**
 *
 * @package    GemsFaq
 * @subpackage Controller
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

/**
 *
 * @package    GemsFaq
 * @subpackage Controller
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class FaqPageSetupController extends \Gems_Controller_ModelSnippetActionAbstract
{
    /**
     * The snippets used for the autofilter action.
     *
     * @var mixed String or array of snippets name
     */
    protected $autofilterParameters = [
        'extraSort' => [
            'gfp_action' => SORT_ASC,
            'gfp_label' => SORT_ASC,
            ],
    ];

    /**
     * Variable to set tags for cache cleanup after changes
     *
     * @var array
     */
    public $cacheTags = array('faq_pages');

    /**
     * The snippets used for the create and edit actions.
     *
     * @var mixed String or array of snippets name
     */
    protected $createEditSnippets = ['ModelFormSnippetGeneric', 'InfoSnippet'];

    /**
     * The parameters used for the edit actions, overrules any values in
     * $this->createEditParameters.
     *
     * When the value is a function name of that object, then that functions is executed
     * with the array key as single parameter and the return value is set as the used value
     * - unless the key is an integer in which case the code is executed but the return value
     * is not stored.
     *
     * @var array Mixed key => value array for snippet initialization
     */
    protected $editParameters = [
        'infoTitle'     => 'getInfoTitle',
        'inlineExample' => true,
        'pageId'        => 'getPageId',
    ];

    /**
     * @var GemsFaq\Util\FaqUtil
     */
    public $faqUtil;
    
    /**
     * @var \Gems_Menu 
     */
    public $menu;

    /**
     * @var \Gems_Util
     */
    public $util;

    /**
     * The parameters used for the show action
     *
     * When the value is a function name of that object, then that functions is executed
     * with the array key as single parameter and the return value is set as the used value
     * - unless the key is an integer in which case the code is executed but the return value
     * is not stored.
     *
     * @var array Mixed key => value array for snippet initialization
     */
    protected $showParameters = [
        'infoTitle'     => 'getInfoTitle',
        'inlineExample' => true,
        'pageId'        => 'getPageId',
        ];
    
    /**
     * The snippets used for the show action
     *
     * @var mixed String or array of snippets name
     */
    protected $showSnippets = ['Generic\\ContentTitleSnippet', 'ModelItemTableSnippetGeneric', 'InfoSnippet'];
    
    /**
     * @inheritDoc
     */
    protected function createModel($detailed, $action)
    {
        $model = new \MUtil_Model_TableModel('gemsfaq__pages');

        $regex = new \Zend_Validate_Regex('/^[-a-z0-9]+$/');
        $regex->setMessage($this->_('Only lowercase letters, numbers and - dashes are allowed.'), \Zend_Validate_Regex::NOT_MATCH);
        
        $model->set('gfp_action', 'label', $this->_('Action name'),
            'description', $this->_('A unique, readable lowercase name containing only letters, numbers and - dashes.'),
            'filters[lcase]', 'StringToLower',
            'validators[regex]', $regex,
            'validators[unique]', $model->createUniqueValidator('gfp_action', ['gfp_id'])
            );

        $model->set('gfp_label', 'label', $this->_('(Short) menu name'));
        $model->set('gfp_title', 'label', $this->_('Full title'));
        
        $model->set('gfp_menu_position', 'label', $this->_('Menu position'),
            'multiOptions', $this->faqUtil->getMenuPositionOptions()
        );
        $model->set('gfp_menu_relative', 'label', $this->_('Relative position'),
                    'multiOptions', $this->util->getTranslated()->getEmptyDropdownArray() + $this->faqUtil->getRelativeMenuOptions()
        );

        $model->set('gfp_active', 'label', $this->_('Active'),
                    'elementClass', 'Checkbox',
                    'multiOptions', $this->util->getTranslated()->getYesNo()
        );
        // $model->setDeleteValues('gfi_active', 0);
        $model->addColumn(new \Zend_Db_Expr("CASE WHEN gfp_active = 1 THEN '' ELSE 'deleted' END"), 'row_class');

        \Gems_Model::setChangeFieldsByPrefix($model, 'gfp');
        
        return $model;
    }

    /**
     * @return mixed
     */
    public function getInfoTitle()
    {
        $page = $this->faqUtil->getInfoPageById($this->getPageId());
        
        if ($page) {
            if ($page['gfp_title']) {
                return $page['gfp_title'];
            } else {
                return $page['gfp_label'];
            }
        } 
    }
    
    /**
     * @return mixed|null
     */
    public function getPageId()
    {
        return $this->getRequest()->getParam(\MUtil_Model::REQUEST_ID);
    }
    
    /**
     * Helper function to allow generalized statements about the items in the model.
     *
     * @param int $count
     * @return $string
     */
    public function getTopic($count = 1)
    {
        return $this->plural('info page', 'info pages', $count);
    }
}