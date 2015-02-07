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
        $idLanguage = $this->getConfig()->language_default;
        
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            
            $inputFilter->add(array(
                'name'     => 'vacancyId',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
            
            $inputFilter->add(array(
                'name'     => 'title_'.$idLanguage,
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
            
            $inputFilter->add(array(
                'name'     => 'text_'.$idLanguage,
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
                        ),
                    ),
                ),
            ));
 
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
     * Get languages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLanguages()
    {
        return $this->languages;
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
    /**
     * Get descriptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDescriptions() {
        return $this->descriptions;
    }
    
    /**
     * Get Title Languages for current vacancy
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getLanguagesTitleForVacancy() {
        return $this->descriptions->map(function($entity){
            return $entity->getLanguage()->getTitle(); 
        });
    }
    
    /**
     * Get translate for current vacancy
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getTitlesForVacancy() {
        return $this->descriptions->map(function($entity){
           return $entity->getVacancyTitle(); 
        });
    }
    
    /**
     * Get all vacancies match the filter
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @param array $filter
     * @return array
     */
    public function getVacanciesAll(\Doctrine\ORM\EntityManager $em, $filter = array()) {
        $idLanguage = $this->getConfig()->language_default;
        $qb = $em->createQueryBuilder();
        $qb->select('d, v')
            ->from('Application\Entity\Descriptions', 'd')
            ->join('d.vacancies', 'v')
            ->where('v.enabled = 1')
        ;
        if(!empty($filter['lang'])) {
            $qb->andWhere($qb->expr()->orX(
                'd.languageId = ?1', 'd.languageId = ?2'
            ))
            ->setParameter(1, (int) $filter['lang'])
            ->setParameter(2, $idLanguage)
            ;
        }
        if(!empty($filter['dep'])) {
            $qb->join('v.departmentsDepartment', 'dep')
                ->andWhere('dep.departmentId IN (?3)')
                ->setParameter(3, $filter['dep'])
            ;
        }
        $qb->orderBy('d.languageId', 'DESC')
            ->addOrderBy('v.vacancyId', 'DESC')
        ;
        $query = $qb->getQuery();
        $query->useResultCache(TRUE);
        $result = array();
        foreach ($query->getArrayResult() as $item) {
            if (!array_key_exists($item['vacancies']['vacancyId'], $result)) {
                $result[$item['vacancies']['vacancyId']] = $item;
            }
        }
        return $result;
    }
    
    /**
     * 
     * Get all departments for vacancies
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @return array
     */
    public function getDepartnetsForVacancies(\Doctrine\ORM\EntityManager $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('d')
            ->from('Application\Entity\Departments', 'd')
            ->join('d.vacanciesVacancy', 'v')
            ->groupBy('d.departmentId')
        ;
        $query = $qb->getQuery();
        $query->useResultCache(TRUE);
        return $query->getArrayResult();
    }
    
    /**
     * 
     * Get all languages for vacancies
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @return array
     */
    public function getLanguagesForVacancies(\Doctrine\ORM\EntityManager $em) {
        $qb = $em->createQueryBuilder();
        $qb->select('l')
            ->from('Application\Entity\Languages', 'l')
            ->join('l.descriptions', 'd')
            ->groupBy('l.languageId')
        ;
        $query = $qb->getQuery();
        $query->useResultCache(TRUE);
        return $query->getArrayResult();
    }
    
    public function getConfig() {
        return new \Zend\Config\Config(include __DIR__ . '/../../../config/config.php');
    }
}
