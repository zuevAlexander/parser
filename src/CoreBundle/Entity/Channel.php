<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CoreBundle\Entity\User;

/**
 * Channel
 *
 * @ORM\Table(name="channel")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ChannelRepository")
 */
class Channel
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=100, unique=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=10)
     */
    private $format;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="Feed", mappedBy="channel")
     */
    private $feeds;

    public function __construct() {
        $this->feeds = new ArrayCollection();
    }
    
    /**
     * @return string
     */
    public function getFeeds()
    {
        return $this->feeds;
    }
    
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Channel
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set format
     *
     * @param string $format
     *
     * @return Channel
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

 
}

