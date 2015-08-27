<?php
namespace Cadastro\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;

class Evento extends Form
{

    public function __construct()
    {
        parent::__construct();
        
        $elementOrFieldset = new Text('nome');
    	$elementOrFieldset->setLabel('Nome:');
    	$elementOrFieldset->setAttribute('autofocus', 'autofocus');
    	
    	$this->add($elementOrFieldset);
	 
    	$elementOrFieldset = new Hidden('codigo');
    	 
    	$this->add($elementOrFieldset);
    	 
    	$elementOrFieldset = new Submit('gravar');
    	$elementOrFieldset->setValue('gravar');
    	 
    	$this->add($elementOrFieldset);
    }
}