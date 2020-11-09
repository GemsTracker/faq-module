<?php

/**
 *
 * @package    GemsFaq
 * @subpackage FaqParts.php
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq;

/**
 *
 * @package    GemsFaq
 * @subpackage FaqParts.php
 * @license    New BSD License
 * @since      Class available since version 1.8.8
 */
class FaqParts extends \Gems_Loader_TargetLoaderAbstract
{
    const GROUP_PART = 'Group';
    const ITEM_PART  = 'Item';

    /**
     * Each part type must implement an interface derived
     *
     * @var array containing partType => partInterface for all part classes
     */
    protected $_partClasses = [
        self::GROUP_PART => 'GemsFaq\\Parts\\GroupPartInterface',
        self::ITEM_PART  => 'GemsFaq\\Parts\\ItemPartInterface',
        ];
    
    /**
     *
     * @param string $partType An screen subdirectory (may contain multiple levels split by '/'
     * @return array An array of type prefix => classname
     */
    protected function _getDirs($partType)
    {
        $paths = [];
        if (DIRECTORY_SEPARATOR == '/') {
            $mainDir = str_replace('\\', DIRECTORY_SEPARATOR, $partType);
        } else {
            $mainDir = $partType;
        }
        foreach ($this->_dirs as $name => $dir) {
            $prefix = $name . '\\Parts\\'. $partType . '\\';
            $fullPath = $dir . DIRECTORY_SEPARATOR . 'Parts' . DIRECTORY_SEPARATOR . $mainDir;
            if (file_exists($fullPath)) {
                $paths[$prefix] = $fullPath;
            }
        }

        return $paths;
    }

    /**
     * Part class for a faq part. This class or interface should at the very least
     * implement the PartInterface.
     *
     * @see \GemsFaq\Parts\PartInterface
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
     * Returns a list of selectable screens with an empty element as the first option.
     *
     * @param string $partType The type (i.e. lookup directory with an associated class) of the parts to list
     * @return \Gems_tracker_TrackerEventInterface or more specific a $screenClass type object
     */
    protected function _listParts($partType)
    {
        $partClass = $this->_getType($partType);
        $paths     = $this->_getDirs($partType);

        return $this->listClasses($partClass, $paths, 'getPartName');
    }

    /**
     * Loads and initiates an screen class and returns the class (without triggering the screen itself).
     *
     * @param string $partName The class name of the individual screen to load
     * @param string $partType The type (i.e. lookup directory with an associated class) of the screen
     * @return \Gems_tracker_TrackerEventInterface or more specific a $screenClass type object
     */
    protected function _loadPart($partName, $partType)
    {
        $partClass = $this->_getType($partType);

        // \MUtil_Echo::track($partName);
        if (! class_exists($partName, true)) {
            // Autoload is used for Zend standard defined classnames,
            // so if the class is not autoloaded, define the path here.
            $filename = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Parts' . DIRECTORY_SEPARATOR .
                strtolower($partType) . DIRECTORY_SEPARATOR . $partName . '.php';

            if (! file_exists($filename)) {
                throw new \Gems_Exception_Coding("The part '$partName' of type '$partType' does not exist at location: $filename.");
            }
            // \MUtil_Echo::track($filename);

            include($filename);
        }

        $part = new $partName();

        if (! $part instanceof $partClass) {
            throw new \Gems_Exception_Coding("The part '$partName' of type '$partType' is not an instance of '$partClass'.");
        }

        if ($part instanceof \MUtil_Registry_TargetInterface) {
            $this->applySource($part);
        }

        return $part;
    }

    /**
     *
     * @return array partname => string
     */
    public function listGroupParts()
    {
        return $this->_listParts(self::GROUP_PART);
    }
}