<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Descriptions
 *
 * @ORM\Table(name="DescriptionsVacancy", indexes={@ORM\Index(name="fk_Descriptions_Vacancies1_idx", columns={"vacancy_id"}), @ORM\Index(name="fk_Descriptions_Languages1_idx", columns={"language_id"})})
 * @ORM\Entity
 */
class Descriptions
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="language_id", type="integer", nullable=false)
     */
    private $languageId;

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
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Application\Entity\Vacancies", inversedBy="descriptions", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vacancy_id", referencedColumnName="vacancy_id", unique=false)
     * })
     */
    private $vacancies;

    /**
     * @var \Application\Entity\Languages
     *
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Application\Entity\Languages", inversedBy="descriptions", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="language_id", unique=false)
     * })
     */
    private $languages;



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
     * @return Descriptions
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
     * @return Descriptions
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
     * @return Descriptions
     */
    public function setVacancy(\Application\Entity\Vacancies $vacancy)
    {
        $this->vacancies = $vacancy;

        return $this;
    }

    /**
     * Get vacancy
     *
     * @return \Application\Entity\Vacancies 
     */
    public function getVacancy()
    {
        return $this->vacancies;
    }
    
    /**
     * Get languageId
     *
     * @return string 
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Set language
     *
     * @param \Application\Entity\Languages $language
     * @return Descriptions
     */
    public function setLanguage(\Application\Entity\Languages $language)
    {
        $this->languages = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Application\Entity\Languages 
     */
    public function getLanguage()
    {
        return $this->languages;
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
}
