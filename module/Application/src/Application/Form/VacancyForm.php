<?php

namespace Application\Form;

use Zend\Form\Form;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VacancyForm extends Form implements ObjectManagerAwareInterface
{
    protected $objectManager;
    
    public function __construct($name = null, $entityManager)
    {
        parent::__construct($name);

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-vertical');
        
        $this->add(array(
            'name' => 'vacancyId',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'enabled',
            'type' => 'Checkbox',
            'options' => array(
                'label' => 'Enabled',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
                'class' => 'btn btn-default'
            ),
        ));
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
                'name' => 'departmentsDepartment',
                'options' => array(
                    'object_manager' => $entityManager,
                    'target_class'   => 'Application\Entity\Departments',
                    'label' => 'Departments',
                    'property' => 'title',
                ),
                'attributes' => array(
                    'class' => "form-control"
                )
            )
        );
    }
    
    public function setDescriptions($langId) {
        $this->add(array(
            'name' => "id[{$langId}]",
            'type' => 'Hidden'
        ));
            
        $this->add(array(
            'name' => "languageId[{$langId}]",
            'type' => 'Hidden',
            'attributes' => array(
                "value" => $langId
            )
        ));
                
        $this->add(array(
            'name' => "title[{$langId}]",
            'type' => 'text',
            'options' => array(
                'label' => "Title"
            ),
            'attributes' => array(
                'class' => "form-control",
                'size' => 50
            )
        ));
        
        $this->add(array(
            'name' => "text[{$langId}]",
            'type' => 'Textarea',
            'options' => array(
                'label' => "Text"
            ),
            'attributes' => array(
                'class' => "form-control",
                'rows' => 10,
                'cols' => 100
            )
        ));        
    }
    
    public function setLanguages() {
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'languageId',
                'options' => array(
                    'object_manager' => $this->getObjectManager(),
                    'target_class'   => 'Application\Entity\Languages',
                    'label' => 'Language',
                    'property' => 'title',
                ),
                'attributes' => array(
                    'class' => "form-control"
                )
            )
        );
        return $this;
    }
    
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }
    
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}
