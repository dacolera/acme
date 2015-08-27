<?php
namespace Acme\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\Validator\ValidatorChain;
use Zend\Filter\FilterChain;
abstract class ModelAbstract
{
    /**
     * 
     * @var array
     */
    protected $attributes = array();
    
    /**
     * 
     * @var InputFilter
     */
    protected $inputFilter = NULL;
    
    /**
     * 
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    
    public function exchangeArray($array)
    {
        foreach($this->getArrayCopy() as $attribute => $value){
            $this->$attribute = isset($array[$attribute]) ?
            $array[$attribute] : $this->$attribute;
        }
    }
    
    public function getInputFilter()
    {
        if ($this->inputFilter == NULL){
            $this->inputFilter = new InputFilter();
            foreach($this->attributes as $attribute => $inputs){
                $input = new Input($attribute);
                $input->setAllowEmpty($inputs['allowEmpty']);
                $validatorChain = new ValidatorChain();
                $inputs['validators'] =
                isset($inputs['validators']) ? $inputs['validators']
                : array();
                foreach($inputs['validators'] as $validator){
                    $validatorChain->attach($validator);
                }
                $input->setValidatorChain($validatorChain);
                $filterChain = new FilterChain();
                $inputs['filters'] =
                isset($inputs['filters']) ? $inputs['filters']
                : array();
                foreach($inputs['filters'] as $filter){
                    $filterChain->attach($filter);
                } 
                $input->setFilterChain($filterChain);
                $this->inputFilter->add($input);                    
            }
        }
        
        return $this->inputFilter;
    }
    
    public function getSet()
    {
        $set = array();
        foreach($this->attributes as $attribute => $value){
            if (!isset($value['key'])){
                $set[$attribute] = $this->$attribute;
            }
        }
        return $set;
    }    
    
    
    
    
    
    
    
    
}
