<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq;

use GemsFaq\PageParts\GroupPartInterface;
use GemsFaq\PageParts\ItemPartInterface;
use Zalt\Loader\ProjectOverloader;
use Zalt\Loader\ObjectListOverloader;
// use Zalt\Loader\Target\TargetAbstract;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class FaqPageParts extends \MUtil_Registry_TargetAbstract
{
    const GROUP_PART = 'Group';
    const ITEM_PART  = 'Item';

    /**
     * @var Zalt\Loader\ObjectListOverloader
     */
    protected $_objectLister;

    /**
     * Each part type must implement an interface derived
     *
     * @var array containing partType => partInterface for all part classes
     */
    protected $_partClasses = [
        self::GROUP_PART => 'GemsFaq\\PageParts\\GroupPartInterface',
        self::ITEM_PART  => 'GemsFaq\\PageParts\\ItemPartInterface',
    ];

    /**
     *
     * @var \Zend_Db_Adapter_Abstract
     */
    protected $db;

    /**
     * @var array Of prefix => path strings for class lookup
     */
    protected $loaderDirs;

    /**
     * @var \Zalt\Loader\ProjectOverloader
     */
    protected $overLoader;

    /**
     * Part class for a faq part. This class or interface should at the very least
     * implement the PartInterface.
     *
     * @see \GemsFaq\PageParts\PartInterface
     *
     * @param string $partType The type (i.e. lookup directory) to find the associated class for
     * @return string Class/interface name associated with the type
     */
    protected function _getType($partType)
    {
        if (isset($this->_partClasses[$partType])) {
            return $this->_partClasses[$partType];
        } else {
            throw new \Gems_Exception_Coding("No part class exists for part type '$partType'.");
        }
    }
    
    /**
     * Called after the check that all required registry values
     * have been set correctly has run.
     *
     * @return void
     */
    public function afterRegistry()
    {
        $this->_objectLister = new ObjectListOverloader('PageParts', $this->overLoader, $this->loaderDirs);
    }

    /**
     * @param int $groupid
     * @return GroupPartInterface
     */
    public function getGroup($groupId)
    {
        $group = $this->db->fetchRow("SELECT * FROM gemsfaq__groups WHERE gfg_id = ?", $groupId);

        if (! $group) {
            return null;
        }
        
        $part = $this->getGroupPart($group['gfg_display_method']);
        if ($part instanceof GroupPartInterface) {
            $part->exchangeArray($group);
            return $part;
        }

        return null;
    }

    /**
     * @param int $groupid
     * @return array itemId => ItemPartInterface
     */
    public function getGroupItems($groupId)
    {
        $sql = "SELECT * FROM gemsfaq__items WHERE gfi_group_id = ? ORDER BY gfi_id_order, gfi_title";

        $items = $this->db->fetchAll($sql, $groupId);

        if (! $items) {
            return [];
        }

        $output = [];
        foreach ($items as $item) {
            $part = $this->getItemPart($item['gfi_display_method']);
            if ($part instanceof ItemPartInterface) {
                $part->exchangeArray($item);
                $output[$item['gfi_id']] = $part;
            }
        }

        return $output;
    }

    /**
     * @param string $displayMethod
     * @return GroupPartInterface
     */
    public function getGroupPart($displayMethod)
    {
        $subPath = self::GROUP_PART;
        return $this->_objectLister->loadObject($displayMethod, $subPath, $this->_getType($subPath));
    }

    /**
     * @param int $itemId
     * @return ItemPartInterface
     */
    public function getItem($itemId)
    {
        $item = $this->db->fetchRow("SELECT * FROM gemsfaq__items WHERE gfi_id = ?", $itemId);

        if (! $item) {
            return null;
        }

        $part = $this->getItemPart($item['gfi_display_method']);
        if ($part instanceof ItemPartInterface) {
            $part->exchangeArray($item);
            return $part;
        }

        return null;
    }

    /**
     * @param string $displayMethod
     * @return ItemPartInterface
     */
    public function getItemPart($displayMethod)
    {
        $subPath = self::ITEM_PART;
        return $this->_objectLister->loadObject($displayMethod, $subPath, $this->_getType($subPath));
    }

    /**
     * @param string $action
     * @return array
     */
    public function getPageGroups($pageid)
    {
        $sql = "SELECT * FROM gemsfaq__groups WHERE gfg_active = 1 AND gfg_page_id = ? ORDER BY gfg_id_order, gfg_group_name";

        $groups = $this->db->fetchAll($sql, $pageid);
        
        if (! $groups) {
            return [];
        }

        $output = [];
        foreach ($groups as $group) {
            $part = $this->getGroupPart($group['gfg_display_method']);
            if ($part instanceof GroupPartInterface) {
                $part->exchangeArray($group);
                $output[$group['gfg_id']] = $part;
            }    
        }
        
        return $output;
    }

    /**
     *
     * @return array partname => string
     */
    public function listGroupParts()
    {
        $subType = self::GROUP_PART; 
        return $this->_objectLister->listObjects($subType, $this->_getType($subType), 'getPartName');
    }

    /**
     *
     * @return array partname => string
     */
    public function listItemParts()
    {
        $subType = self::ITEM_PART;
        return $this->_objectLister->listObjects($subType, $this->_getType($subType), 'getPartName');
    }
}