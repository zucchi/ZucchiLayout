<?php
namespace ZucchiLayout\Form;

use Zend\Form\Form as ZendForm;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Zend\Filter;


class Install extends ZendForm
{
    /**
     * construct base for security form
     */
    public function __construct()
    {
        parent::__construct('install');
        $this->setAttribute('method', 'post');
        
//         $this->add(array(
//             'name'  => 'csrf',
//             'type' => 'Zend\Form\Element\Csrf',
//             'priority' => 999999,
//         ));

        $this->add(array(
            'name' => 'install',
            'type' => 'Zend\Form\Element\File',
            'attributes' => array(
                'type' => 'file',
            ),
            'options' => array(
                'bootstrap' => array(
                    'style' => 'inline',
                ),
            ),
        ));
        
        $actions = new Fieldset('actions');
        $actions->setAttribute('class', 'form-actions');
        
        $actions->add(array(
            'name' => 'login',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Upload',
                'class' => 'btn btn-primary'
            ),
            'options' => array(
                'bootstrap' => array(
                    'style' => 'inline',
                ),
            ),
        ));
        
        $this->add($actions, array('priority' => -9999));
        
    }
}