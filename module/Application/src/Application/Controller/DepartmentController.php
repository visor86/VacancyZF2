<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity;

class DepartmentController extends AbstractActionController
{
    protected $_objectManager;
    
    public function indexAction() {
        $objectManager = $this->getObjectManager();
        $departments = $objectManager->getRepository('\Application\Entity\Departments')
            ->findAll();
        
        $view = new ViewModel(array(
            'departments' => $departments,
        ));
        
        return $view;
    }
    
    public function addAction() {
        $form = new \Application\Form\DepartmentForm();
        $form->get('submit')->setValue('Add');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $department = new \Application\Entity\Departments();
            $form->setInputFilter($department->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $objectManager = $this->getObjectManager();
                
                $department->exchangeArray($form->getData());
                
                $objectManager->persist($department);
                $objectManager->flush();
                
                return $this->redirect()->toRoute('department');
            } else {
                $message = 'Error while saving department';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        return array('form' => $form);
    }
    
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $objectManager = $this->getObjectManager();
        
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Department id doesn\'t set');
            return $this->redirect()->toRoute('department');
        }
        
        $form = new \Application\Form\DepartmentForm();
        $form->get('submit')->setValue('Save');
        
        $request = $this->getRequest();
        
        $department = $objectManager->find('\Application\Entity\Departments', $id);
            
        if(!$department) {
            $this->flashMessenger()->addErrorMessage(sprintf('Department with id %s doesn\'t exists', $id));
            return $this->redirect()->toRoute('department');
        }
        
        $form->bind($department);
        
        if ($request->isPost()) {
            $form->setInputFilter($department->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $objectManager->flush();
                
                return $this->redirect()->toRoute('department');
            }
        }
        return array('form' => $form, 'id' => $id, 'department' => $department);
    }
    
    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('department');
        }
        $request = $this->getRequest();

        $objectManager = $this->getObjectManager();
        $department = $objectManager->find('\Application\Entity\Departments', $id);
        if($department) {
            $objectManager->remove($department);
            $objectManager->flush();
        }
        
        return $this->redirect()->toRoute('department');
    }
    
    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_objectManager;
    }
}

