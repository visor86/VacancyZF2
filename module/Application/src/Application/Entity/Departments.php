<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Departments
 *
 * @ORM\Table(name="Departments")
 * @ORM\Entity
 */
class Departments implements InputFilterAwareInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="department_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $departmentId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;
    
    private $inputFilter;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Vacancies", mappedBy="departmentsDepartment", cascade={"persist"})
     */
    private $vacanciesVacancy;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vacanciesVacancy = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get departmentId
     *
     * @return integer 
     */
    public function getDepartmentId()
    {
        return $this->departmentId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Departments
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add vacancies
     *
     * @param \Application\Entity\Vacancies $vacancies
     * @return Departments
     */
    public function addVacancy(\Application\Entity\Vacancies $vacancies)
    {
        $this->vacanciesVacancy[] = $vacancies;

        return $this;
    }

    /**
     * Remove vacancies
     *
     * @param \Application\Entity\Vacancies $vacancies
     */
    public function removeVacancy(\Application\Entity\Vacancies $vacancies)
    {
        $this->vacanciesVacancy->removeElement($vacancies);
    }

    /**
     * Get vacancies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVacancies()
    {
        return $this->vacancies;
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
                'name'     => 'departmentId',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
 
            $inputFilter->add(array(
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
            ));
 
            $this->inputFilter = $inputFilter;
        }
 
        return $this->inputFilter;
    }
}
