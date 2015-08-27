<?php
namespace Cadastro\Model;

use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;
use Zend\Validator\Digits;
use Acme\Model\ModelAbstract;
class Evento extends ModelAbstract
{
    /**
     * 
     * @var integer
     */
    public $codigo = NULL;
    /**
     * 
     * @var string
     */
    public $nome = NULL;
    
    public function __construct()
    {
        $this->attributes = array(
        	'codigo' => array(
        	   'allowEmpty' => TRUE,
        	   'validators' => array(),
        	   'key'=>TRUE     
            ),
            'nome' => array(
                'allowEmpty' => FALSE,
                'validators' => array(
            	   new StringLength(array('min'=>3,'max'=>30))   
                )
            ),            
        );
    }
}