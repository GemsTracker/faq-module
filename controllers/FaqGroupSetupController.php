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
class FaqGroupSetupController extends \Gems_Controller_ModelSnippetActionAbstract
{
    /**
     * The snippets used for the autofilter action.
     *
     * @var mixed String or array of snippets name
     */
    protected $autofilterParameters = [
        'extraSort' => [
            'gfp_label' => SORT_ASC,
            'gfg_id_order' => SORT_ASC,
            'gfg_id' => SORT_ASC,
        ],
    ];
    
    /**
     * Variable to set tags for cache cleanup after changes
     *
     * @var array
     */
    public $cacheTags = array('faq_groups');

    /**
     * The snippets used for the create and edit actions.
     *
     * @var mixed String or array of snippets name
     */
    protected $createEditSnippets = ['GroupEditSnippet'];
    
    /**
     * @var \GemsFaq\FaqPageParts
     */
    public $faqParts;

    /**
     * @var GemsFaq\Util\FaqUtil
     */
    public $faqUtil;

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
        'groupId'       => 'getGroupId',
        'inlineExample' => true,
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
        $model = new \Gems_Model_JoinModel('gemsfaq__groups', 'gemsfaq__groups', 'gfg');

        if ($detailed) {
            $model->set('gfg_page_id', 'label', $this->_('Page'),
                        'multiOptions', $this->faqUtil->getInfoPagesList()
            );
        } else {
            $model->addTable('gemsfaq__pages', ['gfg_page_id' => 'gfp_id']);
            $model->set('gfp_label', 'label', $this->_('Page'));
        }

        $model->set('gfg_id_order', 'label', $this->_('Display Order'),
            'description', $this->_('The order of group for display within a page'),
            'default', $this->faqUtil->getGroupOrderDefault(),
            'filters[int]', 'Int',
            'validators[unique]', $model->createUniqueValidator('gfg_id_order', ['gfg_id', 'gfg_page_id'])
            );
        $model->set('gfg_group_name', 'label', $this->_('Group title'));

        $options = $this->faqParts->listGroupParts();
        $model->set('gfg_display_method', 'label', $this->_('Display option'),
                    'default', key($options),
                    'multiOptions', $options,
                    'onchange', 'this.form.submit()'
        ); 

        $model->set('gfg_active', 'label', $this->_('Active'),
                    'elementClass', 'Checkbox',
                    'multiOptions', $this->util->getTranslated()->getYesNo()
        );
        
        
        // $model->setDeleteValues('gfi_active', 0);
        $model->addColumn(new \Zend_Db_Expr("CASE WHEN gfg_active = 1 THEN '' ELSE 'deleted' END"), 'row_class');

        return $model;
    }

    /**
     * @return mixed|null
     */
    public function getGroupId()
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
        return $this->plural('info group', 'info groups', $count);
    }
}