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
class ItemSearchFormSnippet extends \Gems_Snippets_AutosearchFormSnippet
{
    /**
     * @var GemsFaq\FaqPageParts
     */
    public $faqParts;
    
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

        $elements['gfi_group_id'] = $this->_createSelectElement('gfi_group_id', $this->faqUtil->getInfoGroupsList(), $this->_('(all groups)'));
        $elements['gfg_page_id'] = $this->_createSelectElement('gfg_page_id', $this->faqUtil->getInfoPagesList(), $this->_('(all pages)'));
        
        $yesNo      = $this->util->getTranslated()->getYesNo();
        $elements['gfi_active'] = $this->_createSelectElement('gfi_active', $yesNo, $this->_('(any active)'));

        $elements[] = \MUtil_Html::create('br');

        $elements['gfi_display_method'] = $this->_createSelectElement('gfi_display_method', $this->faqParts->listItemParts(), $this->_('(all display options)'));
        return $elements;
    }
}