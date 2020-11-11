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
class ExampleItemPart implements ItemPartInterface
{
    /**
     * @inheritDoc
     */
    public function getBodySettings()
    {
    }

    /**
     * @inheritDoc
     */
    public function exchangeArray(array $data)
    {
    }

    /**
     * @inheritDoc
     */
    public function getExample()
    {
    }

    /**
     * @inheritDoc
     */
    public function getHtmlOutput()
    {
        static $count = 1;
        
        return \MUtil_Html::create('p', "Example item " . $count++);
    }

    /**
     * @inheritDoc
     */
    public function getPartName()
    {
    }
}