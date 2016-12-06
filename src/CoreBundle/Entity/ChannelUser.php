<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChannelUser
 *
 * @ORM\Table(name="channel_user")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\ChannelUserRepository")
 */
class ChannelUser
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Channel")
     * @ORM\JoinColumn(name="channel_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $channel;

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
     * Set user
     *
     * @param integer $user
     *
     * @return ChannelUser
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set channel
     *
     * @param integer $channel
     *
     * @return ChannelUser
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return int
     */
    public function getChannel()
    {
        return $this->channel;
    }
}

