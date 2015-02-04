<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Departments
 *
 * @ORM\Table(name="Departments")
 * @ORM\Entity
 */
class Departments
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

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Vacancies", mappedBy="departmentsDepartment")
     */
    private $vacancies;

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
        $this->vacancies[] = $vacancies;

        return $this;
    }

    /**
     * Remove vacancies
     *
     * @param \Application\Entity\Vacancies $vacancies
     */
    public function removeVacancy(\Application\Entity\Vacancies $vacancies)
    {
        $this->vacancies->removeElement($vacancies);
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
}
