<?php

/**
 *
 * @package    GemsFaq
 * @subpackage Controller
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2020, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */


/**
 *
 * @package    GemsFaq
 * @subpackage Controller
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class InfoController extends \Gems_Controller_Action
{
    /**
     * @var GemsFaq\Util\FaqUtil
     */
    public $faqUtil;

    /**
     * Set to true in child class for automatic creation of $this->html.
     *
     * To initiate the use of $this->html from the code call $this->initHtml()
     *
     * Overrules $useRawOutput.
     *
     * @see $useRawOutput
     * @var boolean $useHtmlView
     */
    public $useHtmlView = true;

    /**
     * @param string $methodName
     * @param array  $args
     * @return null|void
     * @throws \Zend_Controller_Action_Exception
     */
    public function __call($methodName, $args)
    {
        if (\MUtil_String::endsWith($methodName, 'Action')) {
            $page = $this->faqUtil->getInfoPage(substr($methodName, 0, -6));
            if ($page) {
                $this->infoAction($page);
                return null;
            }
        }
        parent::__call($methodName, $args);
    }

    public function infoAction($page) 
    {
        $params['pageId'] = $page['gfp_id'];
        if ($page['gfp_title']) {
            $params['title'] = $page['gfp_title'];
        } else {
            $params['title'] = $page['gfp_label'];
        }
        $this->addSnippet('InfoSnippet', $params);
    }
    
    
}