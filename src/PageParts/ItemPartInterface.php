<?php
        
/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
interface ItemPartInterface extends PartInterface 
{
    /**
     * Get the model settings for the body display 
     *
     * @return array
     */
    public function getBodySettings();
    
    /**
     * Is this item usuable for this locale
     *
     * @return boolean
     */
    public function isForLocale($locale);
}