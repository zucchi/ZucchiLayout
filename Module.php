<?php
/**
 * ZucchiLayout (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiLayout for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiLayout;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\ModuleManager;
use ZucchiLayout\Event\LayoutListener;

use Zucchi\Debug\Debug;

/**
 * Module for the management of application layouts
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiLayout
 * @subpackage Module
 * @category Layout
 */
class Module implements 
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface,
    BootstrapListenerInterface
{
    /**
     * attache new listener for controlling layouts
     * 
     * @param ModuleManager $moduleManager
     */
        public function onBootstrap(EventInterface $e)
        {
            $app = $e->getApplication();
            $events = $app->getEventManager();
            $sm = $app->getServiceManager();
            // replace direct instatiation with $sm to get listened already populated with view and db service 
            $layoutListener = $sm->get('zucchilayout.listener');
            $layoutListener->attach($events);
        }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
//                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * get module specific config
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\ServiceProviderInterface::getServiceConfig()
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'zucchilayout.listener' => function($sm) {
                    $config = $sm->get('config');
                    $listener = new LayoutListener();
                    return $listener;
                },
            ),
        );
    }
    
}
