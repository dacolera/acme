<?php
namespace Cadastro\Model;

use Acme\Model\TableAbstract;
class EventoTable extends TableAbstract
{
    protected $keyName = 'codigo';
    protected $modelClass = 'Cadastro\Model\Evento';   
}