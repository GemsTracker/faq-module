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
 * @since      Class available since version 1.8.8
 */
abstract class GroupAbstract extends \MUtil_Translate_TranslateableAbstract implements GroupPartInterface
{
    /**
     * @var Source data 
     */
    protected $data;

    /**
     * @var \GemsFaq\FaqPageParts
     */
    protected $faqParts;
    
    /**
     * @inheritDoc
     */
    public function exchangeArray(array $data)
    {
        $this->data = $data;
        
        return $this;
    }

    /**
     * @return array
     */
    protected function getGroupItems()
    {
        return $this->faqParts->getGroupItems($this->data['gfg_id']);
    }
    
    /**
     * Helper function for snippets returning a sequence of Html items.
     *
     * @return \MUtil_Html_Sequence
     */
    protected function getHtmlSequence()
    {
        return new \MUtil_Html_Sequence();
    }
}