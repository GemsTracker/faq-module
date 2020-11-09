<?php

/**
 *
 * @package    GemsFaq
 * @subpackage Parts\Part
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\Parts\Group;

/**
 *
 * @package    GemsFaq
 * @subpackage Parts\Part
 * @license    New BSD License
 * @since      Class available since version 1.8.8
 */
class InfoTitlePart extends MUtil_Translate_TranslateableAbstract 
    implements \GemsFaq\Parts\GroupPartInterface
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
        $this->_('Info with group title');
    }
}