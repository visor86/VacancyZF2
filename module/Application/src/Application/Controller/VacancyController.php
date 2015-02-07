<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Application\Entity;

class VacancyController extends BaseController
{
    
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
                try {
                    foreach ($languages as $language) {
                        if (!empty($request->getPost("title_{$language->languageId}"))) {
                            $description = new \Application\Entity\Descriptions();
                            $description->setVacancyText($request->getPost("text_{$language->languageId}"));
                            $description->setVacancyTitle($request->getPost("title_{$language->languageId}"));
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
    
                    $objectManager->flush();
                    
                    return $this->redirect()->toRoute('vacancy');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                
            } else {
                $message = 'Error while saving Vacancy';
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
            $form->get("title_{$row->getLanguageId()}")->setAttribute("value", $row->getVacancyTitle());
            $form->get("text_{$row->getLanguageId()}")->setAttribute("value", $row->getVacancyText());
        });
        
        $form->bind($vacancy);
        
        $form->get('submit')->setValue('Save');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $form->setInputFilter($vacancy->getInputFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                try {
                    $vacancy->enabled = $request->getPost('enabled');
                    $arId = $request->getPost('id');
                    foreach ($languages as $language) {
                        $languageId = $language->languageId;
                        if (!empty($request->getPost("title_{$languageId}"))) {
                            if (!empty($arId[$languageId])) {
                                $description = $objectManager->find('\Application\Entity\Descriptions', (int) $arId[$languageId]);
                            } else {
                                $description = new \Application\Entity\Descriptions();
                                $description->setLanguage($language);
                                $description->setVacancy($vacancy);
                            }
                            $description->setVacancyText($request->getPost("text_{$languageId}"));
                            $description->setVacancyTitle($request->getPost("title_{$languageId}"));
                            if (empty($arId[$languageId])) {
                                $objectManager->merge($description);
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
                
                    $objectManager->flush();
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
                
               return $this->redirect()->toRoute('vacancy', array('controller' => 'vacancy' , 'action' => 'edit', 'id' => $id));
            } else {
                $message = 'Error while saving Vacancy';
                $this->flashMessenger()->addErrorMessage($message);
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
            try {
                $objectManager->remove($vacancy);
                $objectManager->flush();
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }
        
        return $this->redirect()->toRoute('vacancy');
    }
}

