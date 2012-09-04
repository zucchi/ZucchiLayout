<?php
/**
 * ZucchiLayout (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiLayout for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace ZucchiLayout\Event;

use Zend\EventManager\SharedEventManagerInterface;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;

use Zucchi\Debug\Debug;

/**
 * Strategy for allowing manipulation of layout 
 * 
 * @category   Layout
 * @package    ZucchiLayout
 * @subpackage Layout
 */
class LayoutListener
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();


    /**
     * Attach the a listener to the specified event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            'Zend\Mvc\Application',
            MvcEvent::EVENT_RENDER, 
            array($this, 'prepareLayout'),
            -9998
        );
    }

    /**
     * prepare the layour
     *
     * @param  MvcEvent $e
     * @return Response
     */
    public function prepareLayout(MvcEvent $e)
    {
        $viewModel = $e->getViewModel();
        if (!$viewModel->terminate()) {
            $viewModel->setTemplate('layout/default');
        }
    }
}
