<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Group
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2021, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts\Group;

use GemsFaq\PageParts\ItemPartInterface;
use GemsFaq\PageParts\SetJQueryView;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Group
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class ExpandableGroup extends \GemsFaq\PageParts\GroupAbstract
{
    /**
     *
     * @var \Gems_Util_BasePath
     */
    protected $basepath;

    /**
     * @var \Zend_View
     */
    protected $view;

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

        SetJQueryView::addJQuery($this->view, $this->basepath);
    }


    /**
     * @inheritDoc
     */
    public function getHtmlOutput()
    {
        $seq = $this->getHtmlSequence();

        $divAll    = $seq->div(['class' => 'verticalExpand']);

        $header    = $divAll->div(['class' => 'header']);
        $headerDiv = $header->h3($this->data['gfg_group_name'] . ' ', array('class' => 'title faq'));

        $span       = $headerDiv->span(array('class' => 'header-caret fa fa-chevron-right'))->raw('&nbsp;');

        $divContent = $divAll->div(['class' => 'content faq', 'style' => 'display: none;']);

        foreach ($this->getGroupItems() as $item) {
            if ($item instanceof ItemPartInterface) {
                $divContent->append($this->getItemDiv($item->getHtmlOutput()));
            }
        }

        return  $seq;
    }

    /**
     * @inheritDoc
     */
    public function getPartName()
    {
        return $this->_('Click & see group');;
    }
}