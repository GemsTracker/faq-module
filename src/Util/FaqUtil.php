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
 * @since      Class available since version 1.8.8
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
        $this->menu = $menu;
        
        try {
            $pages = $this->db->fetchAll("SELECT * FROM gemsfaq__pages WHERE gfp_active = 1");
        } catch (\Zend_Db_Statement_Exception $zdse) {
            \MUtil_Echo::track($zdse->getMessage());
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
                    $target->addPage($page['gfp_label'], 'faq.see.' . $page['gfp_action'], 'info', $page['gfp_action'], $options);
                }
            }
        }
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

    public function getGroupDisplaySnippets()
    {
        return [
            'faqTitle' => $this->_('FAQ with group title'),
            'faqNoTitle' => $this->_('FAQ without group title'),
            'foldable'   => $this->_('Click on title to display group'),
            'infoTitle' => $this->_('Info with group title'),
            'infoNoTitle' => $this->_('Info without group title'),
        ];
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
     * @param string $action
     * @return mixed
     */
    public function getInfoPages()
    {
        $sql = "SELECT gfp_id, gfp_label FROM gemsfaq__pages ORDER BY gfp_label";
        
        return  $this->_getSelectPairsCached(__FUNCTION__, $sql, null, 'faq_pages');
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