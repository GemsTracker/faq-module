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
     * @var \MUtil_Acl
     */
    public $acl;
    
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
    public $cacheTags = ['faq_pages', 'gems_acl', 'roles', 'group', 'groups'];

    /**
     * The snippets used for the create and edit actions.
     *
     * @var mixed String or array of snippets name
     */
    protected $createEditSnippets = ['PageEditSnippet', 'InfoSnippet'];

    /**
     * @var \Zend_Db_Adapter_Abstract
     */
    public $db;
    
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
     * The snippets used for the index action, before those in autofilter
     *
     * @var mixed String or array of snippets name
     */
    protected $indexStartSnippets = array('Generic\\ContentTitleSnippet', 'PageSearchFormSnippet');
    
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

        $model->addColumn(new \Zend_Db_Expr("(SELECT COUNT(gfg_id) FROM gemsfaq__groups WHERE gfp_id = gfg_page_id)"), 'group_count');
        $model->set('group_count', 'label', $this->_('Groups'),
                    'elementClass', 'Exhibitor');

        if ($detailed) {
            $model->addColumn('gfp_action', 'roles');
            $options = $this->util->getDbLookup()->getRoles();
            unset($options['master']);
            // \MUtil_Echo::track($options);
            
            $model->set('roles', 'label', $this->_('For roles'),
                        'description', $this->_('Roles that can see this page'),
                        'elementClass', 'MultiCheckbox',
                        'formatFunction', [$this, 'formatRoles'],
                        'multiOptions', $options
            );
            $model->setOnLoad('roles', [$this, 'loadRoles']);
            $model->setOnSave('roles', [$this, 'saveRoles']);
        }

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

    /**
     * @param $value
     * @return string
     */
    public function formatRoles($value) 
    {
        if (is_array($value)) {
            return \MUtil_Html_Sequence::createSequence(['glue' => '<br/>'], $value);
            // return \MUtil_Html::create('ul', $value, ['class' => 'list-unstyled']);
        }    
        
        return $value;
    }
    
    /**
     * A ModelAbstract->setOnLoad() function that takes care of transforming a
     * dateformat read from the database to a \Zend_Date format
     *
     * @see \MUtil_Model_ModelAbstract
     *
     * @param mixed $value The value being saved
     * @param boolean $isNew True when a new item is being saved
     * @param string $name The name of the current field
     * @param array $context Optional, the other values being saved
     * @param boolean $isPost True when passing on post data
     * @return [] roles
     */
    public function loadRoles($value, $isNew = false, $name = null, array $context = array(), $isPost = false)
    {
        if (is_array($value)) {
           return $value;
        } elseif ($isNew) {
           return [];
        }
        $roles = $this->acl->getRolesForPrivilege('faq.see.' . $context['gfp_action']);
        unset($roles['master']);
        
        return array_values($roles);
    }
    /**
     * A ModelAbstract->setOnSave() function that returns the input
     * date as a valid date.
     *
     * @see \MUtil_Model_ModelAbstract
     *
     * @param mixed $value The value being saved
     * @param boolean $isNew True when a new item is being saved
     * @param string $name The name of the current field
     * @param array $context Optional, the other values being saved
     * @return null
     */
    public function saveRoles($value, $isNew = false, $name = null, array $context = array())
    {
        // \MUtil_Echo::track(func_get_args());
        $privilege = 'faq.see.' . trim($this->db->quote($context['gfp_action']), '\'');
        $value     = (array) $value;
        
        $roles     =  $this->acl->getRoles();
        $roles     = array_combine($roles, $roles);
        unset($roles['master']);

        $addedTo = 0;
        $checked = $value + $this->acl->getChildRoles($value);

        $removedFrom = 0;
        $unchecked   = array_diff($roles, $checked);
        // \MUtil_Echo::track($checked, $unchecked);
        
        foreach ($checked as $role) {
            $privRole = $this->acl->getPrivileges($role);
            $privileges = array_combine($privRole[\Zend_Acl::TYPE_ALLOW], $privRole[\Zend_Acl::TYPE_ALLOW]);
            $privileges[$privilege] = $privilege;

//            \MUtil_Echo::track($role, implode(',', $privileges));
            $addedTo += $this->db->update(
                'gems__roles', 
                ['grl_privileges' => implode(',', $privileges)], 
                $this->db->quoteInto("grl_name = ? AND grl_privileges NOT LIKE '%$privilege%'", $role));
        }
        foreach ($unchecked as $role) {
            $privRole = $this->acl->getPrivileges($role);
            $privileges = array_combine($privRole[\Zend_Acl::TYPE_ALLOW], $privRole[\Zend_Acl::TYPE_ALLOW]);
            unset($privileges[$privilege]);

//            \MUtil_Echo::track($role, implode(',', $privileges));
            $removedFrom += $this->db->update(
                'gems__roles',
                ['grl_privileges' => implode(',', $privileges)],
                $this->db->quoteInto("grl_name = ? AND grl_privileges LIKE '%$privilege%'", $role));
        }
        if ($addedTo) {
            $this->addMessage(sprintf($this->plural('Access granted to %d role', 'Access granted to %d roles', $addedTo), $addedTo));            
        }
        if ($removedFrom) {
            $this->addMessage(sprintf($this->plural('Access removed for %d role', 'Access removed for %d roles', $removedFrom), $removedFrom));            
        }
        
        return null; 
    }
}