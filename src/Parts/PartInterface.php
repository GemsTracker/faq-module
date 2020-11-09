<?php
        
/**
 *
 * @package    GemsFaq
 * @subpackage Parts
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\Parts;

/**
 *
 * @package    GemsFaq
 * @subpackage Parts
 * @license    New BSD License
 * @since      Class available since version 1.8.8
 */
interface PartInterface 
{
    /**
     * Get example html how this part will look
     *
     * @return string
     */
    public function getExample();

    /**
     * Get the name to use in dropdowns for this condition
     *
     * @return string
     */
    public function getPartName();


}