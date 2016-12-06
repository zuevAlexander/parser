<?php

namespace CoreBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use CoreBundle\Entity\Channel;
use CoreBundle\Entity\User;
use CoreBundle\Entity\ChannelUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use CoreBundle\Entity\Feed;


class FeedHandler
{

    /**
     * @var \Symfony\Bridge\Doctrine\RegistryInterface
     */
    protected $doctrine;

    /**
     * ChannelHandler constructor.
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $doctrine
     */
    public function __construct(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getAllFeeds()
    {
        $em = $this->doctrine->getManager();
        return $em->getRepository('CoreBundle:Feed')->findAll();
    }    
    
    public function getFeedByUrl($url)
    {
        $em = $this->doctrine->getManager();
        return $em->getRepository('CoreBundle:Feed')->findBy(array('link' => $url));
    }

    public function getFeedByChannel($url)
    {
        $em = $this->doctrine->getManager();
        return $em->getRepository('CoreBundle:Feed')->findBy(array('link' => $url));
    }
    
    public function createFeed(Feed $feed)
    {
        $em = $this->doctrine->getManager();
        $em->persist($feed);
        $em->flush();
        
        return 'Channe has been saved successfully';

    }

}