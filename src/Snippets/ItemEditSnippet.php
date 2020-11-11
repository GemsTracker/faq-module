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
class ItemEditSnippet extends \Gems_Snippets_ModelFormSnippetGeneric
{
    /**
     * @var \GemsFaq\FaqPageParts
     */
    public $faqParts;

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

        if (isset($this->formData['gfi_display_method']) && $this->formData['gfi_display_method']) {
            $part = $this->faqParts->getItemPart($this->formData['gfi_display_method']);
            
            if ($part) {
                $htmlDiv->div(['class' => 'info-pages inline-example'])
                        ->div(['class' => 'info-main', 'renderClosingTag' => true])->div($part->getExample());
            }
        }
        return $htmlDiv;
    }

}