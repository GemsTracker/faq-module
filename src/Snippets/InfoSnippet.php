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
 * @since      Class available since version 1.9.1
 */
class InfoSnippet extends \MUtil_Snippets_SnippetAbstract
{
    /**
     * @var \GemsFaq\FaqPageParts
     */
    protected $faqParts;

    /**
     * @var \GemsFaq\Util\FaqUtil
     */
    protected $faqUtil; 

    /**
     * @var int Show a single group
     */
    protected $groupId;
    
    /**
     * @var string
     */
    protected $infoTitle;

    /**
     * @var bool 
     */
    protected $inlineExample = false;
    
    /**
     * @var int
     */
    protected $pageId;

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
        $seq = new \MUtil_Html_Sequence();
        
        if ($this->infoTitle) {
            $seq->h1($this->infoTitle);
        }

        if ($this->groupId) {
            $groups = [$this->faqParts->getGroup($this->groupId)];
        } else {
            $groups = $this->faqParts->getPageGroups($this->pageId);
        }
        
        foreach ($groups as $group) {
            if ($group instanceof GroupPartInterface) {
                $seq->append($group->getHtmlOutput());
            }
        }
        
        return $this->faqUtil->getInfoDiv($seq);
    }
    
    /**
     * The place to check if the data set in the snippet is valid
     * to generate the snippet.
     *
     * When invalid data should result in an error, you can throw it
     * here but you can also perform the check in the
     * checkRegistryRequestsAnswers() function from the
     * {@see \MUtil_Registry_TargetInterface}.
     *
     * @return boolean
     */
    public function hasHtmlOutput()
    {
        return $this->pageId || $this->groupId;
    }
}