<?php
namespace Acme\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\I18n\Translator;
use Zend\Validator\AbstractValidator;

abstract class ControllerAbstract extends AbstractActionController
{
    protected $tableClass = NULL;
    protected $table = NULL;
    protected $formClass = NULL;
    protected $route = NULL;
    protected $modelClass = NULL;

    private function initTranslator($form)
    {       
        $filename = realpath(__DIR__ . '/../../../../../data/languages');        
        
        $filename .= '/pt_BR/Zend_Validate.php';
        
        $translator = new Translator();
        $translator->addTranslationFile('phpArray', $filename);
        
        AbstractValidator::setDefaultTranslator($translator);

        $form->setInputFilter($this->getModel()->
        getInputFilter());
        $form->isValid();
    }   
  
    public function indexAction()
    {
        $models = $this->getTable()->getModels();
        return array('models'=>$models);    
    }
    
    public function editAction()
    {
        $key = $this->params('key', NULL);
        
        if (isset($_SESSION['form'])){
            $form = $_SESSION['form'];
            unset($_SESSION['form']);
            $this->initTranslator($form);
        } else {
            $formClass = $this->formClass;
            $form = new $formClass();
            if (!empty($key)){
                $model = $this->getTable()->getModel($key);
                $form->bind($model);
            }            
        }
        $form->setAttribute('method','post');
        $form->setAttribute('action',$this->url()
            ->fromRoute($this->route,array(
        	   'controller' => $this->helper()->getControllerName(),
               'action' => 'save' 
        )));
        return array('form'=>$form);
    }    
    
    public function saveAction()
    {
        $model = $this->getModel();
        $formClass = $this->formClass;
        $form = new $formClass();
        $form->setInputFilter($model->getInputFilter());
        $form->bind($model);
        if (!$form->isValid()){
            $_SESSION['form'] = $form;            
            return $this->helper()->goToRoute($this->route,'edit');
        }
        $this->getTable()->save($model);
        return $this->helper()->goToRoute($this->route);        
    }
    
    public function deleteAction()
    {
        $key = $this->params('key',NULL);
        $this->getTable()->delete($key);
        return $this->helper()->goToRoute($this->route);        
    }
    
    protected function getModel()
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass();
        $model->exchangeArray($_POST);
        return $model;      
    }
    
    protected function getTable()
    {
        if ($this->table == NULL){
            $this->table = $this->getServiceLocator()
            ->get($this->tableClass);
        }
        return $this->table;
    }
}