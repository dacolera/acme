<?php
namespace Cadastro\Model;

use Zend\Validator\StringLength;
use Zend\Validator\NotEmpty;
use Zend\Validator\Digits;
use Acme\Model\ModelAbstract;
use Zend\Filter\StringToUpper;
use Zend\Filter\StripTags;
use Zend\I18n\Filter\Alnum;
use Zend\Filter\Callback;
use Acme\Filter\Spaces;
use Acme\Validator\Vogais;
class Contato extends ModelAbstract
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
    /**
     * 
     * @var string
     */
    public $telefone = NULL;
    
    public function __construct()
    {
        $this->attributes = array(
        	'codigo' => array(
        	   'allowEmpty' => TRUE,
        	   'validators' => array(),
        	   'key' => TRUE  
            ),
            'nome' => array(
                'allowEmpty' => FALSE,
                'filters' => array(
                   new Alnum(true), 
                   //new Callback('capitalizePlus'), 
            	   new StringToUpper('UTF-8'),
                   new StripTags(),
                   new Spaces()   
                ),
                'validators' => array(
                   new Vogais(), 
            	   new StringLength(array('min'=>3,'max'=>30))
                )
            ),
            'telefone' => array(
                'allowEmpty' => TRUE,
                'validators' => array(
            	   new Digits()   
                )
            ),           
        );
    }
}