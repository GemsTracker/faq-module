<?php

/**
 *
 * @package    GemsFaq
 * @subpackage Parts\Group
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\Parts\Group;

/**
 *
 * @package    GemsFaq
 * @subpackage Parts\Group
 * @license    New BSD License
 * @since      Class available since version 1.8.8
 */
class InfoNoTitlePart extends InfoTitlePart
{
    /**
     * @inheritDoc
     */
    public function getPartName()
    {
        $this->_('Info without group title');
    }
}