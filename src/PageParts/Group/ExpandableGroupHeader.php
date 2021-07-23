<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Group
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2021, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts\Group;

use GemsFaq\PageParts\ItemPartInterface;
use GemsFaq\PageParts\SetJQueryView;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Group
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class ExpandableGroupHeader extends ExpandableGroup
{
    /**
     * @inheritDoc
     */
    public function getHtmlOutput()
    {
        $seq = $this->getHtmlSequence();

        $divAll    = $seq->div(['class' => 'verticalExpand headed']);

        $header    = $divAll->div(['class' => 'header']);
        $headerDiv = $header->h2($this->data['gfg_group_name'] . ' ', array('class' => 'title faq'));

        $span       = $headerDiv->span(array('class' => 'header-caret fa fa-chevron-right'))->raw('&nbsp;');

        $divContent = $divAll->div(['class' => 'content faq', 'style' => 'display: none;', 'renderClosingTag' => true]);

        foreach ($this->getGroupItems() as $item) {
            if ($item instanceof ItemPartInterface) {
                $divContent->append($this->getItemDiv($item->getHtmlOutput()));
            }
        }

        return  $seq;
    }

    /**
     * @inheritDoc
     */
    public function getPartName()
    {
        return $this->_('Click & see group with header');;
    }
}
