<?php

/**
 *
 * @package    GemsFaq
 * @subpackage Snippets
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\Snippets;

use GemsFaq\PageParts\GroupPartInterface;

/**
 *
 * @package    GemsFaq
 * @subpackage Snippets
 * @license    New BSD License
 * @since      Class available since version 1.8.8
 */
class InfoSnippet extends \MUtil_Snippets_SnippetAbstract
{
    /**
     * @var \GemsFaq\FaqPageParts
     */
    public $faqParts;

    /**
     * @var int
     */
    public $pageId;

    /**
     * @var string
     */
    public $title;

    /**
     * Create the snippets content
     *
     * This is a stub function either override getHtmlOutput() or override render()
     *
     * @param \Zend_View_Abstract $view Just in case it is needed here
     * @return \MUtil_Html_HtmlInterface Something that can be rendered
     */
    public function getHtmlOutput(\Zend_View_Abstract $view)
    {
        $html = \MUtil_Html::div(['class' => 'info-pages']);

        if ($this->title) {
            $html->h1($this->title);
        }
        
        $groups = $this->faqParts->getPageGroups($this->pageId);
        
        foreach ($groups as $group) {
            if ($group instanceof GroupPartInterface) {
                $html->append($group->getHtmlOutput());
            }
        }
        
        return $html;
    }
}