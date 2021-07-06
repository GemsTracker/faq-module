<?php

/**
 *
 * @package    GemsFaq
 * @subpackage Module
 * @author     jvangestel@gmail.com
 * @license    New BSD License
 */

namespace GemsFaq;

use Gems\Modules\ModuleSettingsAbstract;

/**
 *
 * @package    GemsFaq
 * @subpackage Module
 * @license    New BSD License
 * @since      Class available since version 1.9.1
 */
class ModuleSettings extends ModuleSettingsAbstract
{
    /**
     * @var string
     */
    public static $moduleName = 'faq-module';

    /**
     * @var string
     */
    public static $eventSubscriber = ModuleSubscriber::class;
}
