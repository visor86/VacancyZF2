<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class LanguagesForm extends Form
{
    
    public function __construct($name = null)
    {
        parent::__construct('languages');

        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name' => 'languageId',
            'type' => 'Hidden',
        ));
        
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'options' => array(
                'label' => "Title",
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
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
    }
}
