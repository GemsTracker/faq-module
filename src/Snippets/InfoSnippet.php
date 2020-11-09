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
 * @since      Class available since version 1.8.8
 */
class InfoSnippet extends \MUtil_Snippets_SnippetAbstract
{
    /**
     *
     * @var \Gems_Util_BasePath
     */
    protected $basepath;
    
    /**
     * @var GemsFaq\Util\FaqUtil
     */
    public $faqUtil;
    
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
        $html = $this->getHtmlSequence();

        if ($this->title) {
            $html->h1($this->title);
        }
        
        \MUtil_Echo::classToName($this->faqUtil);
        return $html;

        $view->headScript()->appendFile($this->basepath->getBasePath() .  '/js/jquery.verticalExpand.js');

        $parentItem = $this->menu->getCurrentParent();
        if ($parentItem && (! $parentItem instanceof \Gems_Menu)) {
            $back = \MUtil_Html::create('p');
            $back->append($parentItem->toActionLink($this->_('back')));
        } else {
            $back = \MUtil_Html::br();
        }

        $faqs  = $this->db->fetchAll(
            "SELECT * FROM jc__token_faq WHERE jtf_active = 1 AND jtf_page = ? ORDER BY jtf_id_order",
            $this->page
        );
        $group = null;
        foreach ($faqs as $faq) {
            if ($group != $faq['jtf_group']) {
                if ($group) {
                    $html->append($back);
                }

                $group = $faq['jtf_group'];
                $html->h2($group)->class = 'faq';
            }

            $divAll = $html->div(['class' => 'verticalExpand']);

            $header = $divAll->div(['class' => 'header']);
            $headerDiv = $header->h3($faq['jtf_question'] . ' ', array('class' => 'title'));

            $span = $headerDiv->span(array('class' => 'header-caret fa fa-chevron-right'))->raw('&nbsp;');

            $divContent = $divAll->div(['class' => 'content faq', 'style' => 'display: none;']);
            // $div->h3($faq['jtf_question']);

            $divContent->pInfo()->bbcode($faq['jtf_body']);
        }
        $html->append($back);

        return $html;
    }

}