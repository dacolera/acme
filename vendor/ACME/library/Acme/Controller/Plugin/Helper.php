<?php
namespace Acme\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Helper extends AbstractPlugin
{
    public function goToRoute($route, $action = 'index')
    {
        $controller = $this->getController();
        $controllerName = $this->getControllerName();        
        return $controller->redirect()->toRoute($route,array(
            'controller' => $controllerName,
            'action' => $action
        ));
    }
        
    public function getControllerName()
    {
        $class = explode('\\',get_class($this->getController()));
        $class = end($class);
        return str_replace('Controller','',$class);
    }    
}
