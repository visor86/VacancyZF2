<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Vacancies
 *
 * @ORM\Table(name="Vacancies")
 * @ORM\Entity
 */
class Vacancies implements InputFilterAwareInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="vacancy_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $vacancyId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Departments", inversedBy="vacanciesVacancy", cascade={"persist"})
     * @ORM\JoinTable(name="Vacancies_Departments",
     *   joinColumns={
     *     @ORM\JoinColumn(name="Vacancies_vacancy_id", referencedColumnName="vacancy_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="Departments_department_id", referencedColumnName="department_id")
     *   }
     * )
     */
    private $departmentsDepartment;
    
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Descriptions", mappedBy="vacancies", cascade={"persist"})
     */
    private $descriptions;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Languages", inversedBy="vacancies", cascade={"persist"})
     * @ORM\JoinTable(name="Descriptions",
     *   joinColumns={
     *     @ORM\JoinColumn(name="vacancy_id", referencedColumnName="vacancy_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="language_id", referencedColumnName="language_id")
     *   }
     * )
     */
    private $languages;
    
    private $inputFilter; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->departmentsDepartment = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property) 
    {
        return $this->$property;
    }
  
    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) 
    {
        $this->$property = $value;
    }
  
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy() 
    {
        return get_object_vars($this);
    }
    
    /**
     * Helper function.
     */
    public function exchangeArray($data)
    {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = ($val !== null) ? $val : null;
            }
        }
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
 
            $inputFilter->add(array(
                'name'     => 'vacancyId',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
            
            /*$inputFilter->add(array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            ));*/
 
            $this->inputFilter = $inputFilter;
        }
 
        return $this->inputFilter;
    }

    /**
     * Add departmentsDepartment
     *
     * @param \Application\Entity\Departments $departmentsDepartment
     * @return Vacancies
     */
    public function addDepartments(\Application\Entity\Departments $departments)
    {
        $this->departmentsDepartment[] = $departments;

        return $this;
    }

    /**
     * Remove departmentsDepartment
     *
     * @param \Application\Entity\Departments $departmentsDepartment
     */
    public function removeDepartments(\Application\Entity\Departments $departments)
    {
        $this->departmentsDepartment->removeElement($departments);
    }

    /**
     * Get departmentsDepartment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDepartments()
    {
        return $this->departmentsDepartment;
    }
    
    /**
     * Clear departmentsDepartment
     *
     * @return Vacancies 
     */
    public function clear()
    {
        $this->departmentsDepartment = new \Doctrine\Common\Collections\ArrayCollection();
        
        return $this;
    }
}
