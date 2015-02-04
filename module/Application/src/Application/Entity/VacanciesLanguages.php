<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VacanciesLanguages
 *
 * @ORM\Table(name="Vacancies_Languages", indexes={@ORM\Index(name="fk_Vacancies_Languages_Vacancies1_idx", columns={"vacancy_id"}), @ORM\Index(name="fk_Vacancies_Languages_Languages1_idx", columns={"language_id"})})
 * @ORM\Entity
 */
class VacanciesLanguages
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="vacancy_title", type="string", length=255, nullable=true)
     */
    private $vacancyTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="vacancy_text", type="text", nullable=true)
     */
    private $vacancyText;

    /**
     * @var \Application\Entity\Vacancies
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Vacancies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vacancy_id", referencedColumnName="vacancy_id")
     * })
     */
    private $vacancy;

    /**
     * @var \Application\Entity\Languages
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Languages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="language_id")
     * })
     */
    private $language;



    /**
     * Set id
     *
     * @param integer $id
     * @return VacanciesLanguages
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set vacancyTitle
     *
     * @param string $vacancyTitle
     * @return VacanciesLanguages
     */
    public function setVacancyTitle($vacancyTitle)
    {
        $this->vacancyTitle = $vacancyTitle;

        return $this;
    }

    /**
     * Get vacancyTitle
     *
     * @return string 
     */
    public function getVacancyTitle()
    {
        return $this->vacancyTitle;
    }

    /**
     * Set vacancyText
     *
     * @param string $vacancyText
     * @return VacanciesLanguages
     */
    public function setVacancyText($vacancyText)
    {
        $this->vacancyText = $vacancyText;

        return $this;
    }

    /**
     * Get vacancyText
     *
     * @return string 
     */
    public function getVacancyText()
    {
        return $this->vacancyText;
    }

    /**
     * Set vacancy
     *
     * @param \Application\Entity\Vacancies $vacancy
     * @return VacanciesLanguages
     */
    public function setVacancy(\Application\Entity\Vacancies $vacancy)
    {
        $this->vacancy = $vacancy;

        return $this;
    }

    /**
     * Get vacancy
     *
     * @return \Application\Entity\Vacancies 
     */
    public function getVacancy()
    {
        return $this->vacancy;
    }

    /**
     * Set language
     *
     * @param \Application\Entity\Languages $language
     * @return VacanciesLanguages
     */
    public function setLanguage(\Application\Entity\Languages $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Application\Entity\Languages 
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
