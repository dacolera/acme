<?php
namespace Acme\View\Helper;

use Zend\View\Helper\AbstractHelper;

class EasyUrl extends AbstractHelper
{
    public function __invoke($route,$controller,$action = 'index')
    {
        return $this->view->url($route, array(
            'controller' => $controller,
            'action' => $action
        ));    	
    }
}