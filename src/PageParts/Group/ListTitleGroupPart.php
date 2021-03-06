<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Group
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts\Group;

use GemsFaq\PageParts\ItemPartInterface;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Group
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class ListTitleGroupPart extends \GemsFaq\PageParts\GroupAbstract implements \GemsFaq\PageParts\GroupPartInterface
{
    /**
     * @var bool 
     */
    protected $showTitle = true;
    
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
            $seq->h2($this->data['gfg_group_name'])->class = 'faq';    
        }

        foreach ($this->getGroupItems() as $item) {
            if ($item instanceof ItemPartInterface) {
                $seq->append($this->getItemDiv($item->getHtmlOutput()));
            }
        }
        
        return  $seq;
    }
    
    /**
     * @inheritDoc
     */
    public function getPartName()
    {
        return $this->_('List with group title');
    }
}