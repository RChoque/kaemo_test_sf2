<?php

namespace KaemoTestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Type;

/**
 * Movie
 *
 * @ORM\Table(name="movies")
 * @ORM\Entity(repositoryClass="KaemoTestBundle\Entity\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=25, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     * @Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="realisator", type="string", length=255, nullable=true)
     */
    private $realisator;

    /**
     * Set title
     *
     * @param string $title
     * @return Order
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
     * Set date
     *
     * @param string $date
     * @return Order
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set realisator
     *
     * @param integer $realisator
     * @return Order
     */
    public function setRealisator($realisator)
    {
        $this->realisator = $realisator;

        return $this;
    }

    /**
     * Get realisator
     *
     * @return integer 
     */
    public function getRealisator()
    {
        return $this->realisator;
    }

    
}
