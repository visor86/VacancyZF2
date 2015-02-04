<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vacancies
 *
 * @ORM\Table(name="Vacancies")
 * @ORM\Entity
 */
class Vacancies
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
     * @ORM\ManyToMany(targetEntity="Application\Entity\Departments", inversedBy="vacanciesVacancy")
     * @ORM\JoinTable(name="vacancies_departments",
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
     * Constructor
     */
    public function __construct()
    {
        $this->departmentsDepartment = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get vacancyId
     *
     * @return integer 
     */
    public function getVacancyId()
    {
        return $this->vacancyId;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Vacancies
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Add departmentsDepartment
     *
     * @param \Application\Entity\Departments $departmentsDepartment
     * @return Vacancies
     */
    public function addDepartmentsDepartment(\Application\Entity\Departments $departmentsDepartment)
    {
        $this->departmentsDepartment[] = $departmentsDepartment;

        return $this;
    }

    /**
     * Remove departmentsDepartment
     *
     * @param \Application\Entity\Departments $departmentsDepartment
     */
    public function removeDepartmentsDepartment(\Application\Entity\Departments $departmentsDepartment)
    {
        $this->departmentsDepartment->removeElement($departmentsDepartment);
    }

    /**
     * Get departmentsDepartment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDepartmentsDepartment()
    {
        return $this->departmentsDepartment;
    }
}
