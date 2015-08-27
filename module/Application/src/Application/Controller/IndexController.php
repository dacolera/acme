<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable;
use Zend\Permissions\Acl\Acl;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Where;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $mensagens = '';
        if ($this->flashMessenger()->hasMessages()){
            $mensagens = $this->flashMessenger()->getMessages();
            $mensagens = implode(',',$mensagens);
            $this->flashMessenger()->clearMessages();
        }
        
        return new ViewModel(array('mensagens'=>$mensagens));
    }
    
    public function loginAction()
    {    
        $nome = $this->getRequest()->getPost('nome');
        $senha = $this->getRequest()->getPost('senha');
        
        if (empty($nome) || empty($senha)){
            return $this->redirect()->toRoute('application');
        }
        
        $autenticador = new AuthenticationService();
        
        $zendDb = $this->getServiceLocator()
        ->get('Zend\Db\Adapter');
        
        $adapter = new DbTable($zendDb);
        $adapter->setTableName('usuarios')
        ->setIdentityColumn('nome')
        ->setCredentialColumn('senha')
        ->setIdentity($nome)
        ->setCredential(md5($senha));
        
        $autenticador->setAdapter($adapter);
        
        $result = $autenticador->authenticate();
        
        if ($result->isValid()){
            $contents = $adapter->getResultRowObject(NULL,'senha');
            $autenticador->getStorage()->write($contents);
            $_SESSION['acl'] = $this->getAcl($contents);
            return $this->redirect()->toRoute('cadastro');
        } else {
            foreach($result->getMessages() as $message){
                $this->flashMessenger()->addMessage($message);
            }
            return $this->redirect()->toRoute('application');
        }
    }
    
    public function logoutAction()
    {
        $autenticador = new AuthenticationService();
        $autenticador->clearIdentity();
        return $this->redirect()->toRoute('application');
    }
    
    public function getAcl($usuario)
    {
        $acl = new Acl();
        
        $select = new Select('papeis_usuario');
        $select->join(array('p' => 'papeis'),
            'papeis_usuario.codigo_papel = p.codigo')
        ->where(array('papeis_usuario.codigo_usuario'
                        => $usuario->codigo));    
        $zendDb = $this->getServiceLocator()
        ->get('Zend\Db\Adapter');
        
        $sql = $select->getSqlString($zendDb->getPlatform());
        $statement = $zendDb->query($sql);
        $resultSet = $statement->execute();
        
        $valueSet = array();
        foreach($resultSet as $record){
            $acl->addRole($record['nome']);
            $valueSet[] = $record['codigo'];
        }

        $select = new Select('recursos');
        
        $sql = $select->getSqlString($zendDb->getPlatform());
        $statement = $zendDb->query($sql);
        $resultSet = $statement->execute();
        
        foreach($resultSet as $record){
            $acl->addResource($record['nome']);
        }
        
        $where = new Where();
        $where->in('recursos_papel.codigo_papel',$valueSet);
        
        $select = new Select('recursos_papel');
        $select->join('recursos',
        'recursos_papel.codigo_recurso = recursos.codigo',
        array('recurso' => 'nome'))
            ->join('papeis',
            'recursos_papel.codigo_papel = papeis.codigo',
            array('papel' => 'nome'))
        ->where($where);        
        
        $sql = $select->getSqlString($zendDb->getPlatform());
        
        $statement = $zendDb->query($sql);
        $resultSet = $statement->execute();        
        
        foreach($resultSet as $record){
            $acl->allow($record['papel'], $record['recurso']);
        }       
        
        return $acl;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
