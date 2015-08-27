<?php
namespace Cadastro\Controller;

use Acme\Controller\ControllerAbstract;
class EventoController extends ControllerAbstract
{
    protected $modelClass = 'Cadastro\Model\Evento';
    protected $tableClass = 'EventoTable';
    protected $formClass = 'Cadastro\Form\Evento';
    protected $route = 'cadastro';    
}
