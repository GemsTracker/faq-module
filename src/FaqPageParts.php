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

use MUtil\Translate\TranslateableTrait;
use Zalt\Loader\ProjectOverloader;
use Zalt\Loader\Target\TargetInterface;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @license    New BSD License
 * @since      Class available since version 1.8.8
 */
class FaqPageParts extends \MUtil_Registry_TargetAbstract
{
    use TranslateableTrait;
    
    const GROUP_PART = 'Group';
    const ITEM_PART  = 'Item';

    /**
     * The prefix/path location to look for classes.
     *
     * The standard value is
     * - <Project_name> => application/classes
     * - Gems => library/Gems/classes
     *
     * But an alternative could be:
     * - Demopulse => application/classes
     * - Pulse => application/classes
     * - Gems => library/Gems/classes
     *
     * @var array Of prefix => path strings for class lookup
     */
    protected $_dirs;
    
    /**
     * @var string Subdir for overloading
     */
    protected $_subDir = 'PageParts';

    /**
     * @var \Zalt\Loader\ProjectOverloader
     */
    protected $_subLoader;

    /**
     * @var array Of prefix => path strings for class lookup
     */
    protected $loaderDirs;

    /**
     * @var \Zalt\Loader\ProjectOverloader
     */
    protected $overLoader;

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
            $prefix = $name . '\\'. $this->_subDir . '\\' . $partType . '\\';
            $fullPath = $dir . DIRECTORY_SEPARATOR . $mainDir;
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
     * @param $subFolder
     * @return $this
     */
    public function addSubFolder($subFolder)
    {
        return $this;
    }

    /**
     * Called after the check that all required registry values
     * have been set correctly has run.
     *
     * @return void
     */
    public function afterRegistry()
    {
        $this->_subLoader = $this->overLoader->createSubFolderOverloader($this->_subDir);
        
        foreach ($this->loaderDirs as $name => $dir) {
            $sub = $dir . '\\' . $this->_subDir;
            if (file_exists($sub)) {
                $this->_dirs[$name] = $sub;
            }
        } 
    }

    /**
     * Returns a list of selectable classes with an empty element as the first option.
     *
     * @param string $classType The class or interface that must me implemented
     * @param array  $paths Array of prefix => path to search
     * @param string $nameMEthod The method to call to get the name of the class
     * @return [] array of classname => name
     */
    public function listClasses($classType, $paths, $nameMethod = 'getPartName')
    {
        $results   = array();

        foreach ($paths as $prefix => $path) {
            $parts = explode('_', $prefix, 2);

            try {
                $globIter = new \GlobIterator($path . DIRECTORY_SEPARATOR . '*.php');
            } catch (\RuntimeException $e) {
                // We skip invalid dirs
                continue;
            }

            foreach($globIter as $fileinfo) {
                $filename    = $fileinfo->getFilename();
                $className   = $prefix . substr($filename, 0, -4);
                $classNsName = '\\' . strtr($className, '_', '\\');
                // \MUtil_Echo::track($filename);
                // Take care of double definitions
                if (isset($results[$className])) {
                    continue;
                }

                if (! (class_exists($className, false) || class_exists($classNsName, false))) {
                    include($path . DIRECTORY_SEPARATOR . $filename);
                }

                if ((! class_exists($className, false)) && class_exists($classNsName, false)) {
                    $className = $classNsName;
                }
                $class = new $className();

                if ($class instanceof $classType) {
                    if ($class instanceof \MUtil_Registry_TargetInterface) {
                        $this->overLoader->applyToLegacyTarget($class);
                    } elseif ($class instanceof TargetInterface) {
                        $this->overLoader->applyToTarget($class);                    
                    }

                    \MUtil_Echo::track(get_class($class), $nameMethod, $class->$nameMethod());
                    $results[$className] = trim($class->$nameMethod()) . ' (' . $className . ')';
                }
                // \MUtil_Echo::track($eventName);
            }

        }
        natcasesort($results);
        return $results;
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