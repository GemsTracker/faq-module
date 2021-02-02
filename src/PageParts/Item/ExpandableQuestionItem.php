<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Item
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts\Item;

use GemsFaq\PageParts\HtmlItemAbstract;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Item
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class ExpandableQuestionItem extends HtmlItemAbstract
{
    /**
     *
     * @var \Gems_Util_BasePath
     */
    protected $basepath;

    /**
     * @var bool Check whether the view has been set
     */
    private static $jqueryIsAdded = false;
    
    /**
     * Called after the check that all required registry values
     * have been set correctly has run.
     *
     * This function is no needed if the classes are setup correctly
     *
     * @return void
     */
    public function afterRegistry()
    {
        parent::afterRegistry();
        
        if (self::$jqueryIsAdded) {
            return;
        }

        $this->view->headScript()->appendFile($this->basepath->getBasePath() .  '/faq/jquery.verticalExpand.js');

        self::$jqueryIsAdded = true;
    }
    
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
        
        $divAll = $seq->div(['class' => 'verticalExpand']);

        $header = $divAll->div(['class' => 'header']);
        $headerDiv = $header->h3($this->data['gfi_title'] . ' ', array('class' => 'title'));

        $span = $headerDiv->span(array('class' => 'header-caret fa fa-chevron-right'))->raw('&nbsp;');

        $divContent = $divAll->div(['class' => 'content faq', 'style' => 'display: none;']);

        $divContent->pInfo()->raw($this->data['gfi_body']);

        return $seq;
    }

    /**
     * @inheritDoc
     */
    public function getPartName()
    {
        return $this->_('Click & see question & answer');
    }
}