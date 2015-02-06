<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class VacancyController extends AbstractActionController
{
    protected $_objectManager;
    
    public function indexAction()
    {
        $objectManager = $this->getObjectManager();
        $vacancies = $objectManager->getRepository('\Application\Entity\Vacancies')->findAll();
        
        $view = new ViewModel(array(
            'vacancies' => $vacancies,
        ));
        
        return $view;
    }
    
    public function addAction()
    {
        $objectManager = $this->getObjectManager();
        
        $form = new \Application\Form\VacancyForm('vacancies', $objectManager);
        
        $form->setObjectManager($objectManager);
        
        $languages = $objectManager->getRepository('\Application\Entity\Languages')
            ->findAll();
        foreach ($languages as $language) {
            $form->setDescriptions($language->languageId);
        }
        $form->get('submit')->setValue('Add');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $vacancy = new \Application\Entity\Vacancies();
            
            $form->setInputFilter($vacancy->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $vacancy->enabled = $request->getPost('enabled');
                $arTitle = $request->getPost('title');
                $arText = $request->getPost('text');
                
                foreach ($arTitle as $id => $value) {
                    if (!empty($value)) {
                        $language = $objectManager->find('\Application\Entity\Languages', (int) $id);
                        $description = new \Application\Entity\Descriptions();
                        $description->setVacancyText($arText[$id]);
                        $description->setVacancyTitle($arTitle[$id]);
                        $description->setVacancy($vacancy);
                        $description->setLanguage($language);
                        $objectManager->persist($description);
                    }
                }

                $departments = $objectManager->getRepository('\Application\Entity\Departments')
                    ->findBy(array('departmentId' => $request->getPost('departmentsDepartment')));
                $vacancy->getDepartments();
                foreach ($departments as $department) {
                    $vacancy->addDepartments($department);
                }
                
                try {
                    $objectManager->flush();
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                
                return $this->redirect()->toRoute('vacancy');
            } else {
                $message = 'Error while saving language';
                $this->flashMessenger()->addErrorMessage($message);
            }
        }
        return array('form' => $form, 'languages' => $languages);
    }
    
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $objectManager = $this->getObjectManager();
        
        if (!$id) {
            $this->flashMessenger()->addErrorMessage('Vacancy id doesn\'t set');
            return $this->redirect()->toRoute('vacancy');
        }
        
        $form = new \Application\Form\VacancyForm('vacancy', $objectManager);
        
        $form->setObjectManager($objectManager);
        
        $languages = $objectManager->getRepository('\Application\Entity\Languages')->findAll();
        $vacancy = $objectManager->find('\Application\Entity\Vacancies', $id);
        
        if (!$vacancy) {
            $this->flashMessenger()->addErrorMessage(sprintf('Language with id %s doesn\'t exists', $id));
            return $this->redirect()->toRoute('vacancy');
        }
        
        foreach ($languages as $language) {
            $form->setDescriptions($language->languageId);
        }
        
        $descriptions = $vacancy->descriptions->map(function($row) use (&$form) {
            $form->get("id[{$row->getLanguageId()}]")->setAttribute("value", $row->getId());
            $form->get("title[{$row->getLanguageId()}]")->setAttribute("value", $row->getVacancyTitle());
            $form->get("text[{$row->getLanguageId()}]")->setAttribute("value", $row->getVacancyText());
        });
        
        $form->bind($vacancy);
        
        $form->get('submit')->setValue('Save');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setInputFilter($vacancy->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                $vacancy->enabled = $request->getPost('enabled');
                $arTitle = $request->getPost('title');
                $arText = $request->getPost('text');
                $arId = $request->getPost('id');
                foreach ($arTitle as $languageId => $value) {
                    if (!empty($value)) {
                        if (!empty($arId[$languageId])) {
                            $description = $objectManager->find('\Application\Entity\Descriptions', (int) $arId[$languageId]);
                        } else {
                            $description = new \Application\Entity\Descriptions();
                            $language = $objectManager->find('\Application\Entity\Languages', (int) $id);
                            $description->setLanguage($language);
                            $description->setVacancy($vacancy);
                        }
                        $description->setVacancyText($arText[$languageId]);
                        $description->setVacancyTitle($arTitle[$languageId]);
                        if (empty($arId[$languageId])) {
                            $objectManager->persist($description);
                        }
                    } else {
                        if (!empty($arId[$languageId])) {
                            $description = $objectManager->find('\Application\Entity\Descriptions', $arId[$languageId]);
                            $objectManager->remove($description);
                        }
                    } 
                }
                $departments = $objectManager->getRepository('\Application\Entity\Departments')
                    ->findBy(array('departmentId' => $request->getPost('departmentsDepartment')));
                
                $vacancy->clear();
                foreach ($departments as $department) {
                    $vacancy->getDepartments();
                    $vacancy->addDepartments($department);
                }
                try {
                    $objectManager->flush();
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                
               return $this->redirect()->toRoute('vacancy', array('controller' => 'vacancy' , 'action' => 'edit', 'id' => $id));
            }
        }
        return array('form' => $form, 'id' => $id, 'vacancy' => $vacancy, 'languages' => $languages);
    }
    
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('vacancy');
        }

        $objectManager = $this->getObjectManager();
        $vacancy = $objectManager->find('\Application\Entity\Vacancies', $id);
        if($vacancy) {
            $objectManager->remove($vacancy);
            try {
                $objectManager->flush();
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }
        
        return $this->redirect()->toRoute('vacancy');
    }
    
    protected function getObjectManager()
    {
        if (!$this->_objectManager) {
            $this->_objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_objectManager;
    }
}

