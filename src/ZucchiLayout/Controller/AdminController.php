<?php
/**
 * ZucchiLayout (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchi/ZucchiLayout for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiLayout\Controller;

use Sluggable\Fixture\Validate;

use Zend\View\Model\JsonModel;
use Zend\Config\Reader\Json;
use Zend\Validator\File\NotExists;
use ZucchiLayout\Entity\Layout as LayoutEntity;
use ZucchiLayout\Validator\Layout as LayoutValidator;
use ZucchiLayout\Filter\Layout as LayoutFilter;
use ZucchiAdmin\Controller\AbstractAdminController;
use ZucchiAdmin\Crud\ControllerTrait as CrudControllerTrait;
use Zucchi\Debug\Debug;


/**
 * Controller to allow management web page content
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiLayout
 * @subpackage Controller
 * @category Pages
 *
 */
class AdminController extends AbstractAdminController
{
    use CrudControllerTrait;

    protected $options;

    protected $label = 'Layout';
    
    protected $service = 'zucchilayout.service';
    
    protected $listFields = array(
        'vendor' => 'Vendor',
        'name' => 'Name',
        'description' => 'Description',
    );
    
    /**
     * Action to handle creation of entity
     *
     * @return ViewModel
     */
    public function createAction()
    {
        $model = $this->loadView(
            'zucchi-layout/admin/create'
        );
        return $model; 
    }

    /**
     * Uploads and extracts a layout file to the right place
     *
     * @return JsonModel
     */
    public function uploadAction()
    {
        $path = getcwd() . $this->options->getPath();
        $params = array(
            'success' => false,
            'messages' => array(),
        );

        if ($this->request->isPost()) {
            try {
                $uploader = $this->uploader();

                $uploader->setDestination($path)
                         ->addValidator(new LayoutValidator())
                         ->addFilter(new LayoutFilter(array(
                             'destination' => $path,
                         )))
                ;

                if ($uploader->isValid()) {
                    $params['success'] = $uploader->receive('file');
                }

                if ($params['success']) {
                        $file = $uploader->getFileInfo()['file'];
                        $jsonPath = $path . DIRECTORY_SEPARATOR . $file['name'] . DIRECTORY_SEPARATOR .'layout.json';
                        $reader = new Json();
                    try {
                        $params['layout'] = $reader->fromFile($jsonPath);
                    } catch (Exception $e) {
                        $params['messages'] = $e->getMessage();
                    }
                } else {
                    $params['messages'] = array_merge($params['messages'], $uploader->getMessages());
                }

                if ($uploader->hasErrors() || (isset($params['messages']) && count($params['messages']))) {
                    $params['messages'] = $uploader->getMessages();
                }
            } catch (Exception $e) {
                $params['messages'][] = $e->getMessage();
            }

        } else {
            $params['messages'] = 'You need to POST your new layout to this url';
        }

        $model = new JsonModel($params);
        return $model;
    }
    /**
     * Install a layout to the database
     */
    public function installAction()
    {
        $params = array(
            'success' => false,
        );

        if ($layoutKey = $this->params()->fromPost('layout', false)) {
            // install layout into db
            $path = getcwd() . $this->options->getPath();
            $jsonPath = $path . DIRECTORY_SEPARATOR . $layoutKey . DIRECTORY_SEPARATOR .'layout.json';
            $reader = new Json();
            try {
                $data = $reader->fromFile($jsonPath);
                $layout = new LayoutEntity();

                $layout->fromArray($data);
                $layout->active = false;

                $sl = $this->getServiceLocator();
                $doctrine = $sl->get('doctrine.entitymanager.orm_default');
                $doctrine->persist($layout);
                $doctrine->flush();

                $this->flashMessenger()->addMessage(array(
                    'message'     => sprintf('%1$s sucessfully installed', $layoutKey),
                    'status'      => 'success',
                    'title'       => 'Installed',
                    'dismissable' => true
                ));;

                $params['success'] = true;
            } catch (\Exception $e) {
                $params['messages'][] = $e->getMessage();
            }
        }

        $model = new JsonModel($params);
        return $model;
    }

    public function getOptions()
    {
        return $this->options;
    }
    
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}
