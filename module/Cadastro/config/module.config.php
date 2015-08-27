<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Cadastro\Controller\Index' =>
            'Cadastro\Controller\IndexController',
            'Cadastro\Controller\Contato' => 
            'Cadastro\Controller\ContatoController',
            'Cadastro\Controller\Evento' =>
            'Cadastro\Controller\EventoController'            
        ),
    ),
    'router' => array(
        'routes' => array(
            'cadastro' => array(
                'type'    => 'Segment',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/cadastro[/:controller[/:action[/:key]]]',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Cadastro\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Cadastro' => __DIR__ . '/../view',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
    	   'helper' => 'Acme\Controller\Plugin\Helper',

        )    	
    ),
    'view_helpers' => array(
        'invokables' => array(
            'easyUrl' => 'Acme\View\Helper\EasyUrl'
        )            	
    )
);
