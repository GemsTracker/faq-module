<?php

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2021, Erasmus MC and MagnaFacta B.V.
 * @license    New BSD License
 */

namespace GemsFaq\PageParts;

/**
 *
 * @package    GemsFaq
 * @subpackage PageParts
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class SetJQueryView
{
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
    public static function addJQuery(\Zend_View $view, \Gems_Util_BasePath $basePath)
    {
        if (self::$jqueryIsAdded) {
            return;
        }

        $view->headScript()->appendFile($basePath->getBasePath() .  '/faq/jquery.verticalExpand.js');

        self::$jqueryIsAdded = true;
    }


}