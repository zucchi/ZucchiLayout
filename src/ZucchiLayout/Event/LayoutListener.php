<?php
/**
 * ZucchiLayout (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiLayout for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace ZucchiLayout\Event;

use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\View\Resolver\TemplatePathStack;
use Zucchi\Debug\Debug;
use ZucchiAdmin\Crud\Event\CrudEvent;

/**
 * Strategy for allowing manipulation of layout 
 * 
 * @category   Layout
 * @package    ZucchiLayout
 * @subpackage Layout
 */
class LayoutListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    protected $toDelete = array();


    /**
     * Attach the a listener to the specified event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $shared = $events->getSharedManager();

        $this->listeners = array(
            $events->attach(
                MvcEvent::EVENT_RENDER,
                array($this, 'prepareLayout'),
                -9998
            ),
            $shared->attach(
                'ZucchiLayout\Controller\AdminController',
                CrudEvent::EVENT_DELETE_PRE,
                array($this, 'preDelete')
            ),
            $shared->attach(
                'ZucchiLayout\Controller\AdminController',
                CrudEvent::EVENT_DELETE_POST,
                array($this, 'postDelete')
            ),
        );
    }

    /**
     * remove listeners from events
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        array_walk($this->listeners, array($events,'detach'));
        $this->listeners = array();
    }
    
    /**
     * prepare the layour
     *
     * @param  MvcEvent $e
     * @return Response
     */
    public function prepareLayout(MvcEvent $e)
    {
        $app = $e->getApplication();
        $sm = $app->getServiceManager();


        $viewModel = $e->getViewModel();
        if (!$viewModel->terminate()) {
            try {
                $manager = $sm->get('viewManager');
                $service = $sm->get('zucchilayout.service');
                $options = $sm->get('zucchilayout.options');

                foreach ($manager->getResolver() as $resolver) {
                    if (method_exists($resolver, 'addPath')) {
                        $resolver->addPath(getcwd() . $options->getPath());
                    }
                }

                $service = $sm->get('zucchilayout.service');
                $result = $service->getCurrentLayout();
                $viewModel->setTemplate($result->folder . '/layout');
            } catch (\Exception $e) {
                // nbo layout was found so will fallback to hardcoded layout template
//                throw new \Exception('No layout was found', 500, $e);
            }
        }
    }

    public function preDelete($event)
    {
        $service = $event->getTarget();
        $request = $event->getRequest();

        $id = $request->getQuery()->get('id');

        if (!is_array($id)) { $id = array($id); }

        foreach ($id AS $id) {
            if ($layout = $service->get($id)) {
                $this->toDelete[] = $layout->folder;
            }
        }
        var_dump($this->toDelete);
    }

    public function postDelete($event)
    {
        $sm = $event->getServiceManager();
        $options = $sm->get('zucchilayout.options');

        $path = realpath(getcwd() . $options->getPath());

        foreach ($this->toDelete as $folder) {
            $dir = $path . '/' . $folder;
            $this->removeLayoutFiles($dir);
        }

    }

    protected function removeLayoutFiles($dir)
    {
        if (file_exists($dir)) {
            foreach(glob($dir . '*') as $file) {
                if(is_dir($file)) {
                    $this->removeLayoutFiles($file.'/');
                    rmdir($file);
                } else {
                    unlink($file);
                }
            }
        }
    }
}
