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
     * @inheritDoc
     */
    protected function createModel($detailed, $action)
    {
        $model = new \MUtil_Model_TableModel('gemsfaq__pages');

        $model->set('gfp_action', 'label', $this->_('Action name'),
            'description', $this->_('A unique, readable lowercase name containing only letters, numbers and - dashes.')
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