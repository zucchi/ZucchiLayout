<?php
return array(
    'controllers' => array(
        'factories' => array(
            'zucchi-layout-admin' => function($sm) {
                $controller = new ZucchiLayout\Controller\AdminController();
                $controller->setOptions($sm->getServiceLocator()->get('zucchilayout.options'));
                return $controller;
            }
        ),
    ),
    'navigation' => array(
        'ZucchiAdmin' => array(
            'layout' => array(
                'label' => 'Layout',
                'route' => 'ZucchiAdmin/ZucchiLayout',
                'action' => 'list',
            ),
        )
    ),
    'router' => array(
        'routes' => array(
            'ZucchiAdmin' => array(
                'child_routes' => array(
                    'ZucchiLayout' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/layout[/:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                            ),
                            'defaults' => array(
                                'controller' => 'zucchi-layout-admin',
                                'action' => 'index'
                            )
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
        ),
    ),
    
    'service_manager' => array(
        'invokables' => array(
            'zucchisecurity.auth' => 'ZucchiSecurity\Authentication\Service',
            'zucchisecurity.listener' => 'ZucchiSecurity\Event\SecurityListener',
            'zucchilayout.service' => 'ZucchiLayout\Service\Layout',
        ),
        'factories' => array(
            'zucchilayout.options' => function ($sm) {
                $config = $sm->get('config');
                $options = new \ZucchiLayout\Options\LayoutOptions();
                if (isset($config['ZucchiLayout'])) {
                    $options->setFromArray($config['ZucchiLayout']);
                }
                return $options;
            },
        ),
    ),

    'translator' => array(
        'locale' => 'en_GB',
        'translation_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
//            'ZucchiLayout-Layouts' => getcwd() . '/data/zucchi/layout',
            'ZucchiLayout-View' => __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'zucchilayout_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(realpath(__DIR__ . '/../src/ZucchiLayout/Entity')),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'ZucchiLayout\Entity' => 'zucchilayout_driver',
                )
            )
        )
    ),
    'ZucchiSecurity' => array(
        'permissions' => array(
            'map' => array(
                'ZucchiUser' => array(
                    'settings' => 'update',
                    'install' => 'create',
                ),
            ),
            'roles' => array(
                'layout-manager' => array(
                    'label' => 'Layout Manager',
                    'parents'=>array('admin')
                ),
            ),
            'resources' => array(
                'route' =>array(
                    'ZucchiAdmin' => array(
                        'children' => array('ZucchiLayout'),
                    )
                ),
                'module' => array(
                    'ZucchiLayout',
                ),
            ),
            'rules' => array(
                array(
                    'role' => 'layout-manager',
                    'resource' => array(
                        'route:ZucchiAdmin/ZucchiLayout',
                        'module:ZucchiLayout',
                    ),
                    'privileges' => array(
                        'view', 'create', 'update', 'delete',
                    ),
                ),
            )
        ),
    ),
);
