<?php

/**
 *
 * @package    Controller
 * @subpackage FaqFileUploadController.php
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    No free license, do not copy
 */

/**
 *
 * @package    Controller
 * @subpackage FaqFileUploadController.php
 * @license    No free license, do not copy
 * @since      Class available since version 1.9.1
 */
class FaqFileUploadController extends \Gems_Default_FileActionAbstract
{
    /**
     * Should the action look recursively through the files
     *
     * @var boolean
     */
    public $recursive = true;
    
    /**
     * @inheritDoc
     */
    public function getPath($detailed, $action)
    {
        $dir = GEMS_ROOT_DIR . '/var/uploads/info';
        
        \MUtil_File::ensureDir($dir);
        
        return $dir;
    }
}