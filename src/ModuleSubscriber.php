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

use Gems\Event\Application\GetDatabasePaths;
use Gems\Event\Application\LoaderInitEvent;
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
            GetDatabasePaths::NAME => [
                ['getDatabasePaths'],
            ],
            LoaderInitEvent::NAME => [
                ['initLoader'],
            ],
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
        $translateAdapter = $event->getTranslatorAdapter();
        
        \MUtil_Echo::track('menu');
        
        $prevMenu = $event->getMenu()->findController('comm-job');
        if ($prevMenu) {
            $cjMenu =  $prevMenu->getParent();
            $contMenu = $cjMenu->getParent();

            if ($contMenu instanceof \Gems_Menu_MenuAbstract) {
                $blockMenu = $contMenu->addContainer($translateAdapter->_('FAQ & Manuals'), null, ['order' => $cjMenu->get('order') + 4]);
                $faq = $blockMenu->addBrowsePage($translateAdapter->_('FAQ Setup'), 'faq.setup', 'faq-setup');

//                $blockMenu->addBrowsePage($translateAdapter->_('Studies'), 'prr.studies', 'randomization-study')
//                          ->addAction($translateAdapter->_('Reset study'), 'prr.studies.reset', 'reset');
//                $blockMenu->addBrowsePage($translateAdapter->_('Strata'), 'prr.strata', 'randomization-strata');
//                $blockMenu->addBrowsePage($translateAdapter->_('Values'), 'prr.values', 'randomization-value');
//                $blockMenu->addBrowsePage($translateAdapter->_('Assignments'), 'prr.assignments', 'randomization');
//
//                // See randomization outcome
//                $menu->addHiddenPrivilege('prr.assignments.seeresult', $translateAdapter->_(
//                    'Grant right to see the outcome of a randomization.'
//                ));
            }
        }
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
     */
    public function getDatabasePaths(GetDatabasePaths $event)
    {
        $path = ModuleSettings::getVendorPath() . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'db';
        $event->addPath(ModuleSettings::$moduleName, $path);
    } // */

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
     */
    public function initLoader(LoaderInitEvent $event)
    {
        \MUtil_Echo::track('loader');
        // $event->addByName(new SampleUtil(), 'randomUtil');
    }

    /**
     * @param \Gems\Event\Application\SetFrontControllerDirectory $event
     */
    public function setFrontControllerDirectory(SetFrontControllerDirectory $event)
    {
        $applicationPath = ModuleSettings::getVendorPath() . DIRECTORY_SEPARATOR . 'controllers';
        $event->setControllerDirIfControllerExists($applicationPath);
    } // */
}
