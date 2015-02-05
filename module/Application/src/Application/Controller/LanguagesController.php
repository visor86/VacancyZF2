<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity;

class LanguagesController extends AbstractActionController
{
    protected $_objectManager;
    
    public function indexAction() {
        $objectManager = $this->getObjectManager();
        $languages = $objectManager->getRepository('\Application\Entity\Languages')
            ->findAll();
        
        $view = new ViewModel(array(
            'languages' => $languages,
        ));
        
        return $view;
    }
    
    public function addAction() {
        $form = new \Application\Form\LanguagesForm();
        $form->get('submit')->setValue('Add');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $language = new \Application\Entity\Languages();
            $form->setInputFilter($language->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $objectManager = $this->getObjectManager();
                
                $language->exchangeArray($form->getData());
                
                $objectManager->persist($language);
                $objectManager->flush();
                
                return $this->redirect()->toRoute('languages');
            } else {
                $message = 'Error while saving language';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        return array('form' => $form);
    }
    
    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $objectManager = $this->getObjectManager();
        
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Language id doesn\'t set');
            return $this->redirect()->toRoute('languages');
        }
        
        $form = new \Application\Form\LanguagesForm();
        $form->get('submit')->setValue('Save');
        
        $request = $this->getRequest();
        
        $language = $objectManager->find('\Application\Entity\Languages', $id);
            
        if(!$language) {
            $this->flashMessenger()->addErrorMessage(sprintf('Language with id %s doesn\'t exists', $id));
            return $this->redirect()->toRoute('languages');
        }
        
        $form->bind($language);
        
        if ($request->isPost()) {
            $form->setInputFilter($language->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $objectManager->flush();
                
                return $this->redirect()->toRoute('languages');
            }
        }
        return array('form' => $form, 'id' => $id, 'language' => $language);
    }
    
    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('language');
        }

        $objectManager = $this->getObjectManager();
        $language = $objectManager->find('\Application\Entity\Languages', $id);
        if($language) {
            $objectManager->remove($language);
            $objectManager->flush();
        }
        
        return $this->redirect()->toRoute('languages');
    }
    
    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_objectManager;
    }
}

