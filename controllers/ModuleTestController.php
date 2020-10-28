<?php

/**
 *
 * @package    SampleModule
 * @subpackage Default
 * @author     jvangestel@gmail.com
 * @license    New BSD License
 */

/**
 *
 * @package    GemsRandomizer
 * @subpackage Default
 * @license    New BSD License
 * @since      Class available since version 1.8.8
 */
class ModuleTestController extends \Gems_Controller_Action
{
    public function indexAction()
    {
        $this->initHtml();
        echo "<h1>Hello, the sample module is installed correctly!</h1>";
    }
}
