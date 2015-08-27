<?php
namespace Acme\Service;

use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
class Acl
{
    public function verifyAcl(MvcEvent $event)
    {   
        $autenticador = new AuthenticationService();

        if (!$autenticador->hasIdentity()) return;
        
        $application = $event->getApplication();
        
        $url = $application->getRequest()->getRequestUri();        
        
        $tokens = explode('/',$url);
        
        $baseUrl = $application->getRequest()->getBaseUrl();
        
        if (end($tokens) == 'cadastro' || end($tokens) == 'logout'){
            return;
        }
                
        $temPermissao = FALSE;
        
        $acl = $_SESSION['acl'];
        
        /*
         * url = /app/rota/[controlador]/[acao] 
         */
        
        $size = count($tokens);
        $recurso = ($size == 5) ? $tokens[$size-2] : end($tokens);
     
        $recurso = ucfirst($recurso);
        
        try {
            foreach($acl->getRoles() as $papel){
                $temPermissao = $acl->isAllowed($papel,$recurso);
                if ($temPermissao) break;
            }            
        } catch (Exception $e) {
            $temPermissao = FALSE;
        }
        
        if (!$temPermissao){
            $router = $application->getServiceManager()
            ->get('Router');
        
            $url = $router->assemble(array(
                'controller' => 'Index',
                'action' => 'index'
            ),array(
                'name' => 'cadastro'
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