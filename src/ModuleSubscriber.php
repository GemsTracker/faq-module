<?php

/**
 *
 *
 * @package    SampleModule
 * @subpackage Module
 * @author     jvangestel@gmail.com
 * @license    New BSD License
 */

namespace GemsFaq;

// use Gems\Event\Application\GetDatabasePaths;
// use Gems\Event\Application\LoaderInitEvent;
use Gems\Event\Application\MenuAdd;
// use Gems\Event\Application\ModelCreateEvent;
use Gems\Event\Application\SetFrontControllerDirectory;
// use Gems\Event\Application\TranslatableNamedArrayEvent;
// use Gems\Event\Application\ZendTranslateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 * @package    GemsRandomizer
 * @subpackage Module
 * @license    New BSD License
 * @since      Class available since version 1.8.8
 */
class ModuleSubscriber implements EventSubscriberInterface
{
    /**
     * @return array|\string[][][]
     */
    public static function getSubscribedEvents()
    {
        return [
//            GetDatabasePaths::NAME => [
//                ['getDatabasePaths'],
//            ],
//            LoaderInitEvent::NAME => [
//                ['initLoader'],
//            ],
//            'gems.model.create.conditions' => [
//                ['createConditionModel'],
//            ],
//            'gems.tracker.fielddependencies.get' => [
//                ['getFieldDependencies'],
//            ],
//            'gems.tracker.fieldtypes.get' => [
//                ['getFieldTypes'],
//            ],
            MenuAdd::NAME => [
                ['addToMenu']
            ],
            SetFrontControllerDirectory::NAME => [
                ['setFrontControllerDirectory'],
            ],
//            ZendTranslateEvent::NAME => [
//                ['addTranslation'],
//            ],
        ];
    }

    /**
     * @param \Gems\Event\Application\MenuAdd $event
     */
    public function addToMenu(MenuAdd $event)
    {
        $menu = $event->getMenu();
        $translateAdapter = $event->getTranslatorAdapter();

        $menu->addPage($translateAdapter->_('Sample Module Test'), null, 'module-test', 'index');
    }

    /**
     * @param \Gems\Event\Application\ZendTranslateEvent $event
     * @throws \Zend_Translate_Exception
     * /
    public function addTranslation(ZendTranslateEvent $event)
    {
        $event->addTranslationByDirectory(ModuleSettings::getVendorPath() . DIRECTORY_SEPARATOR . 'languages');
    }

    /**
     * @param \Gems\Event\Application\ZendTranslateEvent $event
     * @throws \Zend_Translate_Exception
     * /
    public function addTranslation(ZendTranslateEvent $event)
    {
        $event->addTranslationByDirectory(ModuleSettings::getVendorPath() . DIRECTORY_SEPARATOR . 'languages');
    }

    /**
     * @param \Gems\Event\Application\ModelCreateEvent $event
     * /
    public function createConditionModel(ModelCreateEvent $event)
    {
        // \MUtil_Echo::track($event->getModel()->getName());
    }

    /**
     * @param \Gems\Event\Application\GetDatabasePaths $event
     * /
    public function getDatabasePaths(GetDatabasePaths $event)
    {
        $path = ModuleSettings::getVendorPath() . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'db';
        $event->addPath(ModuleSettings::$moduleName, $path);
    }// */

    /**
     * @param \Gems\Event\Application\TranslatableNamedArrayEvent $event
     * /
    public function getFieldTypes(TranslatableNamedArrayEvent $event)
    {
        $translateAdapter = $event->getTranslatorAdapter();
        $fieldTypes = [
            'fieldName' => $translateAdapter->_('Field Label'),
        ];

        $event->addItems($fieldTypes);
    }// */

    /**
     * @param \Gems\Event\Application\LoaderInitEvent $event
     * /
    public function initLoader(LoaderInitEvent $event)
    {
        $event->addByName(new SampleUtil(), 'randomUtil');
    }

    /**
     * @param \Gems\Event\Application\SetFrontControllerDirectory $event
     */
    public function setFrontControllerDirectory(SetFrontControllerDirectory $event)
    {
        $applicationPath = ModuleSettings::getVendorPath() . DIRECTORY_SEPARATOR . 'controllers';
        $event->setControllerDirIfControllerExists($applicationPath);
    }
}
