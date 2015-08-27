<?php
namespace Acme\Model;

use Zend\Db\TableGateway\TableGatewayInterface;
abstract class TableAbstract
{
    protected $tableGateway = NULL;
    
    protected $keyName = NULL;
    
    protected $modelClass = NULL;    
    
    public function __construct(TableGatewayInterface $tableGateway){
        $this->tableGateway = $tableGateway;
    }
    
    public function getModels()
    {
        return $this->tableGateway->select();
    }
    
    public function getModel($key)
    {
        $models = $this->tableGateway->select(
	       array($this->keyName => $key)
        );
        if (empty($models)){
            $modelClass = $this->modelClass;
            return new $modelClass();
        } else {
            return $models->current();
        }
    }
    
    public function save(ModelAbstract $model)
    {
        $set = $model->getSet();
        
        $keyName = $this->keyName;
        
        if (empty($model->$keyName)){
            $this->tableGateway->insert($set);
        } else {
            $this->tableGateway->update($set,
            array($keyName => $model->$keyName)    
            );
        }
    }
    
    public function delete($key)
    {
        $this->tableGateway->delete(
            	array($this->keyName => $key)
        );
    }
    
    
    
    
    
}
