<?php
namespace Acme\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Translator\TranslatorInterface;

class Vogais extends AbstractValidator
{
    public function __construct($options = NULL)
    {
        parent::__construct($options);
        $this->abstractOptions['messages'] = array(
        	'Text does not contain vogals'
        );
    }
    
    public function getMessages()
    {
        $messages = parent::getMessages();
        $t = self::$defaultTranslator;
        if ($t instanceof TranslatorInterface){
            foreach($messages as $index => $message){
                $messages[$index] = $t->translate($message);
            }
        }
        return $messages;   
    }    
    
    public function isValid($value)
    {
    	foreach(array('A','E','I','O','U') as $vogal){
    	    if (strpos($value, $vogal) !== FALSE){
    	        return TRUE;
    	    }
    	}
    	return FALSE;
    }
}
