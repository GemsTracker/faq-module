<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Item
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts\Item;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Item
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class AnswerOnly extends ListQuestionItem
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
        return $this->_('List answer only');
    }
}