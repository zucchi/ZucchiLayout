<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'zucchi-layout-admin' => 'ZucchiLayout\Controller\AdminController',
        ),
    ),
    'navigation' => array(
        'ZucchiAdmin' => array(
            'layout' => array(
                'label' => 'Layout',
                'route' => 'ZucchiAdmin/ZucchiLayout',
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
                            )
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'ZucchiLayout' => __DIR__ . '/../view',
        ),
    ),
);
