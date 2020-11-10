<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Part
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts\Group;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Part
 * @license    New BSD License
 * @since      Class available since version 1.8.8
 */
class InfoTitleGroupPart extends \MUtil_Translate_TranslateableAbstract 
    implements \GemsFaq\PageParts\GroupPartInterface
{

    /**
     * @inheritDoc
     */
    public function getExample()
    {
        // TODO: Implement getExample() method.
    }

    /**
     * @inheritDoc
     */
    public function getPartName()
    {
        return $this->_('Info with group title');
    }
}