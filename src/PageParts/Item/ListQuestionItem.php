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

use GemsFaq\PageParts\BbCodeItemAbstract;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Item
 * @license    New BSD License
 * @since      Class available since version 1.8.8
 */
class ListQuestionItem extends BbCodeItemAbstract
{
    /**
     * @var bool
     */
    protected $showTitle = true;

    /**
     * @inheritDoc
     */
    public function getExample()
    {
        // TODO: Implement getExample() method.
    }

    /**
     * Create the snippets content
     *
     * This is a stub function either override getHtmlOutput() or override render()
     *
     * @return \MUtil_Html_HtmlInterface Something that can be rendered
     */
    public function getHtmlOutput()
    {
        $seq = $this->getHtmlSequence();

        if ($this->showTitle) {
            $seq->h4($this->data['gfi_title']);
        }
        $seq->pInfo()->bbcode($this->data['gfi_body']);

        return $seq;
    }

    /**
     * @inheritDoc
     */
    public function getPartName()
    {
        return $this->_('List question & answer');
    }
}