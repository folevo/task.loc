<?php
 return array(
        'view_manager' => array(
             'template_path_stack' => array(
                 __DIR__ . '/../view'
         )
     ),
     'router' => array(
             'routes' => array(
                  'task' => array(
                       'type' => 'Zend\Mvc\Router\Http\Literal',
                      'options' => array(
                              'route' => '/',
                          'defaults' => array(
                                  'controller' => 'Task\Controller\Index',
                                  'action' => 'index',
                          )
                      )
                  ),
                  'data' => array(
                      'type' => 'Zend\Mvc\Router\Http\Literal',
                      'options' => array(
                          'route' => '/data',
                          'defaults' => array(
                              'controller' => 'Task\Controller\Index',
                              'action' => 'data',
                          )
                      )
                  ),
                 'update' => array(
                     'type' => 'Zend\Mvc\Router\Http\Literal',
                     'options' => array(
                         'route' => '/update',
                         'defaults' => array(
                             'controller' => 'Task\Controller\Index',
                             'action' => 'update',
                         )
                     )
                 )

             )

         ),
         'controllers' => array(
                 'invokables' => array(
                      'Task\Controller\Index'
                      => 'Task\Controller\IndexController'
             )
         ),
     'strategies' => array(
         'ViewJsonStrategy',
     ),

 );