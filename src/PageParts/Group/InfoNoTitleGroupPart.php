<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Group
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts\Group;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Group
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class InfoNoTitleGroupPart extends InfoTitleGroupPart
{
    /**
     * @var bool
     */
    protected $showTitle = false;
    
    /**
     * @inheritDoc
     */
    public function getPartName()
    {
        return $this->_('Info without group title');
    }
}