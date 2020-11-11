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

use Zalt\Loader\Translate\TranslateableAbstract;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
abstract class ItemAbstract extends TranslateableAbstract implements ItemPartInterface
{
    /**
     * @var Source data 
     */
    protected $data;

    /**
     * @inheritDoc
     */
    public function exchangeArray(array $data)
    {
        $this->data = $data;
        
        return $this;
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