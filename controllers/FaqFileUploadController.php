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
     * @var A regular expression for allowed file extensions
     */
    protected $_mask;
    
    /**
     * @var \GemsFaq\Util\FaqUtil
     */
    public $faqUtil; 
    
    /**
     * @inheritDoc
     */
    public function getPath($detailed, $action)
    {
        return $this->faqUtil->getDocumentRoot();
    }
    
    /**
     * Creates a model for getModel(). Called only for each new $action.
     *
     * The parameters allow you to easily adapt the model to the current action. The $detailed
     * parameter was added, because the most common use of action is a split between detailed
     * and summarized actions.
     *
     * @param boolean $detailed True when the current action is not in $summarizedActions.
     * @param string $action The current action.
     * @return \MUtil_Model_ModelAbstract
     */
    public function createModel($detailed, $action)
    {
        return $this->faqUtil->getDocumentModel($detailed, $this->getMask());
    }

    /**
     * Return the mask to use for the relpath of the file, use of / slashes for directory seperator required
     *
     * @param boolean $detailed True when the current action is not in $summarizedActions.
     * @param string $action The current action.
     * @return string or null
     */
    public function getMask($detailed, $action)
    {
        if (! $this->_mask) {
            $this->_mask = \MUtil_File::createMask([
                                                       \MUtil_File::$imageExtensions,
                                                       \MUtil_File::$documentExtensions,
                                                       \MUtil_File::$textExtensions,
                                                       \MUtil_File::$videoExtensions]);
        }
    
        return $this->_mask;
    }
}