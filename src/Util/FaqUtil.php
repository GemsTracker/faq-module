<?php

/**
 *
 * @package    GemsFaq
 * @subpackage Util
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\Util;

/**
 *
 * @package    GemsFaq
 * @subpackage Util
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class FaqUtil extends \Gems\Util\UtilAbstract
{
    const MENU_BEFORE = -2;
    const MENU_FIRST_CHILD = -1;
    const MENU_LAST_CHILD = 1;
    const MENU_AFTER = 2;

    /**
     * @var \GemsFaq\FaqPageParts
     */
    protected $faqParts;

    /**
     * @var \Gems_Loader
     */
    protected $loader;
    
    /**
     * @var \Gems_Menu
     */
    protected $menu;

    /**
     * @param $menuKey
     * @return \Gems_Menu_MenuAbstract
     */
    protected function _getMenuItem($menuKey)
    {
        list($controller, $action, $count) = explode('/', $menuKey, 3);

        $item = $this->menu->findController($controller, $action);
        
        for ($i = 0; $count > $i; $i++) {
            if ($item instanceof \Gems_Menu_SubMenuItem) {
                $item = $item->getParent();
            }
        }
        
        return $item;
    }

    /**
     * @param \Gems_Menu_MenuAbstract $menuItem
     * @param int                     $count
     * @return null|string
     */
    protected function _getMenuKey(\Gems_Menu_MenuAbstract $menuItem, $count = 0)
    {
        $key    = $menuItem->get('controller');
        $action = $menuItem->get('action');
        
        if ($key && $action) {
            return $key . '/' . $action . '/' . $count;
        }
        if (! $menuItem->hasChildren()) {
            return null;
        }
        $children = $menuItem->getChildren();
        
        return $this->_getMenuKey(reset($children), ++$count);
    }
    
    /**
     * @param \Gems_Menu_MenuAbstract $item
     * @param array                   $options
     * @param                         $preLabel
     */
    protected function _getSubMenuOptions(\Gems_Menu_MenuAbstract $item, array &$options, $preLabel)
    {
        foreach ($item->getChildren() as $menuItem) {
            if ($menuItem instanceof \Gems_Menu_SubMenuItem) {
                if ($menuItem->get('isInfoPage')) {
                    continue;
                }
                
                $label =  $menuItem->get('label');
                if ($label) {
                    $key = $this->_getMenuKey($menuItem);
                    if ($key && (! isset($options[$key]))) {
                        $options[$key] = $preLabel . $label;

                        // $this->_getSubMenuOptions($menuItem, $options, $options[$key] . ' -> ');
                        $this->_getSubMenuOptions($menuItem, $options, $preLabel . ' -> ');
                    }
                }
            }
        }
    }

    /**
     * @param \Gems_Menu $menu
     */
    public function applyToMenu(\Gems_Menu $menu)
    {
        $this->loader = $this->source;
        $this->menu   = $menu;
        
        try {
            $pages = $this->db->fetchAll("SELECT * FROM gemsfaq__pages WHERE gfp_active = 1");
        } catch (\Zend_Db_Statement_Exception $zdse) {
            // \MUtil_Echo::track($zdse->getMessage());
            return null;
        }
        // \MUtil_Echo::track($pages);
        
        foreach ($pages as $page) {
            $item = $this->_getMenuItem($page['gfp_menu_position']);
            
            if ($item instanceof \Gems_Menu_MenuAbstract) {
                // Reset options
                $options = ['isInfoPage' => true];
                
                switch ($page['gfp_menu_relative']) {
                    case self::MENU_BEFORE:
                        $target = $item->getParent();
                        $options['order'] = $item->get('order') - 5;
                        break;
                    case self::MENU_FIRST_CHILD:
                        $target = $item;
                        $options['order'] = 5;
                        break;
                    case self::MENU_LAST_CHILD:
                        $target = $item;
                        break;
                    case self::MENU_AFTER:
                        $target = $item->getParent();
                        $options['order'] = $item->get('order') + 5;
                        break;
                    default:
                        $target = null;
                        break;
                }
                
                if ($target instanceof \Gems_Menu_MenuAbstract) {
                    // \MUtil_Echo::track($item->get('label'), get_class($item), get_class($target), 'faq.see.' . $page['gfp_action']);
                    $page = $target->addPage($page['gfp_label'], 'faq.see.' . $page['gfp_action'], 'info', $page['gfp_action'], $options);
                    
                    if ($target instanceof \Gems_Menu_SubMenuItem) {
                        $page->setNamedParameters($target->getParameters());
                    }
                }
            }
        }
    }

    /**
     * @param boolean $detailed True when the current action is not in $summarizedActions.
     * @param string  $mask An optional regex file mask, use of / for directory seperator required
     * @return \MUtil_Model_FolderModel
     * @throws \Zend_Exception
     */
    public function getDocumentModel($detailed, $mask = '')
    {
        return $this->loader->getModels()->getFileModel(
            $this->getDocumentRoot(),
            $detailed,
            $mask,
            true,
            true
        );
    }
    
    /**
     * @return string The file upload root
     * @throws \Zend_Exception
     */
    public function getDocumentRoot()
    {
        // $dir = GEMS_ROOT_DIR . '/var/uploads/info';
        $dir = GEMS_WEB_DIR . DIRECTORY_SEPARATOR . 'info';
        
        if (! file_exists($dir)) {
            \MUtil_File::ensureDir($dir);
        }
        
        return $dir;
    }

    /**
     * @return int A new default value for a group
     */
    public function getGroupOrderDefault()
    {
        $val = $this->db->fetchOne("SELECT MAX(gfg_id_order) FROM gemsfaq__groups");
        
        if (is_int($val)) {
            return $val + 10;
        }
        return 10;
    }

    /**
     * @param mixed $content
     * @param false $example
     * @return \MUtil_Html_HtmlInterface
     */
    public function getInfoDiv($content, $example = false)
    {
        $div = \MUtil_Html::div(['class' => 'info-pages' . ($example ? ' inline-example' : '')]);
        $div2 = $div->div(['class' => 'info-main', 'renderClosingTag' => true])
                    ->div($content, ['renderClosingTag' => true]);

        return $div;
    }

    /**
     * @param string $pageid
     * @return mixed
     */
    public function getInfoGroupsList($pageid = null)
    {
        if ($pageid) {
            $sql = "SELECT gfg_id, gfg_group_name 
                        FROM gemsfaq__groups 
                        WHERE gfg_active = 1 AND gfg_page_id = ? 
                        ORDER BY gfg_id_order, gfg_group_name";

            return $this->_getSelectPairsCached(__FUNCTION__ . '_' . intval($pageid), $sql, [$pageid], 'faq_pages');
        }
        return $this->_getSelectPairsCached(
            __FUNCTION__, 
            "SELECT gfg_id, gfg_group_name FROM gemsfaq__groups WHERE gfg_active = 1 ORDER BY gfg_id_order, gfg_group_name",
            null, 
            'faq_groups');
    }
    
    /**
     * @param string $action
     * @return mixed
     */
    public function getInfoPage($action)
    {
        return $this->db->fetchRow("SELECT * FROM gemsfaq__pages WHERE gfp_active = 1 AND gfp_action = ?", $action);
    }

    /**
     * @param string $pageId
     * @return mixed
     */
    public function getInfoPageById($pageId)
    {
        return $this->db->fetchRow("SELECT * FROM gemsfaq__pages WHERE gfp_active = 1 AND gfp_id = ?", $pageId);
    }

    public function getInfoPageAndGroupsList($pageid = null)
    {
        if ($pageid) {
            $sql = "SELECT gfg_id, CONCAT(gfp_title, ' - ', gfg_group_name) 
                        FROM gemsfaq__groups INNER JOIN gemsfaq__pages ON gfg_page_id=gfp_id  
                        WHERE gfg_active = 1 AND gfg_page_id = ? 
                        ORDER BY gfp_title, gfg_id_order, gfg_group_name";

            return $this->_getSelectPairsCached(__FUNCTION__ . '_' . intval($pageid), $sql, [$pageid], 'faq_pages');
        }
        return $this->_getSelectPairsCached(
            __FUNCTION__,
            "SELECT gfg_id, CONCAT(gfp_title, ' - ', gfg_group_name) FROM gemsfaq__groups INNER JOIN gemsfaq__pages ON gfg_page_id=gfp_id WHERE gfg_active = 1 ORDER BY gfp_title, gfg_id_order, gfg_group_name",
            null,
            'faq_groups');
    }
    
    /**
     * @param string $action
     * @return mixed
     */
    public function getInfoPagesList()
    {
        $sql = "SELECT gfp_id, gfp_label FROM gemsfaq__pages ORDER BY gfp_label";
        
        return  $this->_getSelectPairsCached(__FUNCTION__, $sql, null, 'faq_pages');
    }

    /**
     * @param mixed $content
     * @return \MUtil_Html_HtmlInterface
     */
    public function getItemExplanantion($content)
    {
        if (! $content) {
            return;
        }
        $div = \MUtil_Html::div(['class' => 'alert alert-info', 'role' => "alert"]);

        $div->h4($this->_('Instructions'));
        $div->append($content);

        return $div;
    }

    /**
     * @return int A new default value for an item
     */
    public function getItemOrderDefault()
    {
        $val = $this->db->fetchOne("SELECT MAX(gfi_id_order) FROM gemsfaq__items");

        if (is_int($val)) {
            return $val + 10;
        }
        return 10;
    }
    
    /**
     * @return array controller/action => label
     */
    public function getMenuPositionOptions()
    {
        $menuOptions = [];

        $this->_getSubMenuOptions($this->menu, $menuOptions, '');

        // \MUtil_Echo::track($menuOptions);
        return $menuOptions;
    }

    /**
     * @return array
     */
    public function getRelativeMenuOptions()
    {
        return [
            self::MENU_BEFORE      => $this->_('Before - at the same level'),
            self::MENU_FIRST_CHILD => $this->_('As first child'),
            self::MENU_LAST_CHILD  => $this->_('As last child'),
            self::MENU_AFTER       => $this->_('After - at the same level'),
        ];
    }
}