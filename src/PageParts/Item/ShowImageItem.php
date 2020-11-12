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
class ShowImageItem //  extends \GemsFaq\PageParts\ItemAbstract
{
    /**
     * @inheritDoc
     */
    public function getBodySettings()
    {
        // TODO: Implement getBodySettings() method.
    }

    /**
     * @inheritDoc
     */
    public function getExample()
    {
        return \MUtil_Html_ImgElement::img(['src' => 'faq/logo-carefacts.png', 'alt' => 'CareFacts']);
    }

    /**
     * @inheritDoc
     */
    public function getHtmlOutput()
    {
        // TODO: Implement getHtmlOutput() method.
    }

    /**
     * Optional additional instructions
     *
     * @return \MUtil_Html_HtmlInterface Something that can be rendered
     */
    public function getInstructions()
    {
        return $this->_("You can select images after uploading them to Info Files");
    }

    /**
     * @inheritDoc
     */
    public function getPartName()
    {
        return $this->_('Show an image');
    }
}