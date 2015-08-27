<?php
namespace Acme\Service;

use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
class Authentication
{
    public function verifyIdentity(MvcEvent $event)
    {
        $application = $event->getApplication();
        
        $url = $application->getRequest()->getRequestUri();
        
        $tokens = explode('/',$url);
        
        $baseUrl = $application->getRequest()->getBaseUrl();
        
        if (end($tokens) == 'login' || end($tokens) == 'application'
            || end($tokens) == $baseUrl){
            return;
        }
        
        $autenticador = new AuthenticationService();
        
        if (!$autenticador->hasIdentity()){
            $router = $application->getServiceManager()
            ->get('Router');
            
            $url = $router->assemble(array(
            	'controller' => 'Index',
                'action' => 'index'
            ),array(
            	'name' => 'application'
            ));
            
            $response = $application->getResponse();
            
            $response->setHeaders($response->getHeaders()
            ->addHeaderLine('Location',$url));
            
            $response->setStatusCode(302);
            
            $response->sendHeaders();
            exit();            
        }
    }
}