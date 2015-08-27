<?php
namespace Cadastro\Controller;

use Acme\Controller\ControllerAbstract;
class ContatoController extends ControllerAbstract
{
    protected $modelClass = 'Cadastro\Model\Contato';
    protected $tableClass = 'ContatoTable';
    protected $formClass = 'Cadastro\Form\Contato';
    protected $route = 'cadastro';
}
