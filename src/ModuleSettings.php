<?php

/**
 *
 * @package    SampleModule
 * @subpackage Module
 * @author     jvangestel@gmail.com
 * @license    New BSD License
 */

namespace GemsFaq;

use Gems\Modules\ModuleSettingsAbstract;

/**
 *
 * @package    SampleModule
 * @subpackage Module
 * @since      New BSD License
 */
class ModuleSettings extends ModuleSettingsAbstract
{
    /**
     * @var string
     */
    public static $moduleName = 'GemsFaq';

    /**
     * @var string
     */
    public static $eventSubscriber = ModuleSubscriber::class;
}
