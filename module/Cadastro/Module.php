<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Cadastro for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cadastro;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\TableGateway;
use Cadastro\Model\ContatoTable;
use Zend\Session\SessionManager;
use Cadastro\Model\EventoTable;
use Zend\Mvc\I18n\Translator;
use Zend\Validator\AbstractValidator;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        define('DS',DIRECTORY_SEPARATOR);
        $path = str_replace('/',DS,'/../../vendor/ACME/library/Acme');
        $path = realpath(__DIR__ . $path);
        
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                    'Acme' => $path
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $session = new SessionManager();
        $session->start();        
    }
    
    public function getServiceConfig()
    {
        return array(
        	'factories' => array(
        	   'ContatoTable' => function($sm){
        	       $adapter = $sm->get('Zend/Db/Adapter');
        	       $tableGateway = new TableGateway('contatos',$adapter);
        	       $contatoTable = new ContatoTable($tableGateway);
        	       return $contatoTable;
        	   },
        	   'EventoTable' => function($sm){
            	   	$adapter = $sm->get('Zend/Db/Adapter');
            	   	$tableGateway = new TableGateway('eventos',$adapter);
            	   	$eventoTable = new EventoTable($tableGateway);
            	   	return $eventoTable;
        	   }        	   
        )
        );
    }
}
