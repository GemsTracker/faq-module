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

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts\Item
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class VideoItem extends \GemsFaq\PageParts\ItemAbstract
{

    /**
     * @inheritDoc
     */
    public function getBodySettings()
    {
        $regex = new \Zend_Validate_Regex('/^<iframe\\s(.*\\s)src\\s?=.+>.*<\\/iframe>$/');
        $regex->setMessage($this->_('Input must be in the format <iframe src=".." ></iframe>.'), \Zend_Validate_Regex::NOT_MATCH);
        return [
            'label'                     => $this->_('Inline video'),
            'autoInsertNoTagsValidator' => false,
            'cols'                      => 60,
            'description'               => $this->_('Paste the embed iFrame html from the video service (only youtube at the moment).'),
            'elementClass'              => 'Textarea',
            'formatFunction'            => [$this, 'showVideo'],
            'validators[regex]'         => $regex,
            'required'                  => true,
            'rows'                      => 8,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getExample()
    {
        return \MUtil_Html_ImgElement::img(['src' => 'faq/video.png']);   
    }
    
    /**
     * @inheritDoc
     */
    public function getHtmlOutput()
    {
        if ($this->data['gfi_body']) {
            return new \MUtil_Html_Raw($this->data['gfi_body']);
        }
        return \MUtil_Html::create('hr');
    }

    /**
     * Optional additional instructions
     *
     * @return \MUtil_Html_HtmlInterface Something that can be rendered
     */
    public function getInstructions()
    {
        $seq = $this->getHtmlSequence();
        $seq->pInfo()->bbcode($this->_('This currently only works with [b]Youtube[/b]'));
        $seq->h4($this->_('Follow these steps:'));
        $ol = $seq->ol();
        $ol->li($this->_('Go to SHARE on the video page.'));
        $ol->li()->bbcode($this->_('Choose Embed or iFrame of [code]<>[/code] as the sharing method.'));
        $ol->li($this->_('Copy and paste the <iframe ... code.'));
        $seq->pInfo($this->_('An example:'));
        $seq->pre('<iframe width="560" height="315" 
src="https://www.youtube.com/embed/ecIWPzGEbFc" frameborder="0" 
allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
allowfullscreen>
</iframe>');
        
        return $seq;
    }
    
    /**
     * @inheritDoc
     */
    public function getPartName()
    {
        return $this->_('An external video - by iframe');
    }

    /**
     * @param $value
     * @return \MUtil_Html_Creator|\MUtil_Html_HtmlElement
     */
    public function showVideo($value)
    {
        if (empty($value)) {
            $em = \MUtil_Html::create('em');
            $em->raw($this->_('&laquo;empty&raquo;'));

            return $em;
        }

        $div = \MUtil_Html::create('div', array('class' => 'mailpreview'));
        $div->raw($value);

        return $div;
    }
}