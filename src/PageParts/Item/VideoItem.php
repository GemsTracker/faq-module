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
        return [
            'label'          => $this->_('Inline video'),
            'autoInsertNoTagsValidator' => false,
            'cols'           => 60,
            'description'    => $this->_('Paste the include iFrame html from the video service (only youtube at the moment).'),
            'elementClass'   => 'Textarea',
            'formatFunction' => [$this, 'getHtmlOutput'],
            'required'       => true,
            'rows'           => 8,
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
     * @inheritDoc
     */
    public function getPartName()
    {
        return $this->_('An external video - by iframe');
    }
}