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

/**
 *
 * @package    GemsFaq
 * @subpackage Snippets
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class GroupEditSnippet extends \Gems_Snippets_ModelFormSnippetGeneric
{
    /**
     * @var \GemsFaq\FaqPageParts
     */
    public $faqParts;

    /**
     * @var \GemsFaq\Util\FaqUtil
     */
    public $faqUtil;

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
        $htmlDiv = parent::getHtmlOutput($view);

        if (isset($this->formData['gfg_display_method']) && $this->formData['gfg_display_method']) {
            $part = $this->faqParts->getGroupPart($this->formData['gfg_display_method']);
            
            if ($part) {
                $htmlDiv[] = $this->faqUtil->getInfoDiv($part->getExample(), true);
                $htmlDiv[] = $this->faqUtil->getItemExplanantion($part->getInstructions());
            }
        }
        return $htmlDiv;
    }

}