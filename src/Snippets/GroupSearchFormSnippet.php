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
class GroupSearchFormSnippet extends \Gems_Snippets_AutosearchFormSnippet
{
    /**
     * @var GemsFaq\Util\FaqUtil
     */
    public $faqUtil;

    /**
     * Returns a text element for autosearch. Can be overruled.
     *
     * The form / html elements to search on. Elements can be grouped by inserting null's between them.
     * That creates a distinct group of elements
     *
     * @param array $data The $form field values (can be usefull, but no need to set them)
     * @return array Of \Zend_Form_Element's or static tekst to add to the html or null for group breaks.
     */
    protected function getAutoSearchElements(array $data)
    {
        $elements = parent::getAutoSearchElements($data);

        $elements['gfg_page_id'] = $this->_createSelectElement('gfg_page_id', $this->faqUtil->getInfoPagesList(), $this->_('(all pages)'));
        
        $yesNo      = $this->util->getTranslated()->getYesNo();
        $elements['gfg_active'] = $this->_createSelectElement('gfg_active', $yesNo, $this->_('(any active)'));

        return $elements;
    }
}