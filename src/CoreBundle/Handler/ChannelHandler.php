<?php

namespace CoreBundle\Handler;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use CoreBundle\Entity\Channel;
use CoreBundle\Entity\User;
use CoreBundle\Entity\Feed;
use CoreBundle\Entity\ChannelUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Validator\Constraints\Date;


class ChannelHandler
{

    /**
     * @var \Symfony\Bridge\Doctrine\RegistryInterface
     */
    protected $doctrine;

    /**
     * @var \AppBundle\Security\User\WebserviceUserProvider
     */
    private $userProvider;

    /**
     * ChannelHandler constructor.
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $doctrine
     * @param \AppBundle\Security\User\WebserviceUserProvider $userProvider
     */
    public function __construct(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine, \AppBundle\Security\User\WebserviceUserProvider $userProvider)
    {
        $this->doctrine = $doctrine;
        $this->userProvider = $userProvider;
    }

    public function getChannelByToken()
    {
        $em = $this->doctrine->getManager();

        $user = $this->userProvider->getCurrentUser();

        if ($channelsByUser = $em->getRepository('CoreBundle:ChannelUser')->findBy(array('user' => $user))) {
            foreach ($channelsByUser as $item) {
                $channels[] = $item->getChannel();
            }
            
            return $channels;
        }

        return 'Channels were not found';
    }

    public function getAllChannel()
    {
        $em = $this->doctrine->getManager();

        return $em->getRepository('CoreBundle:Channel')->findAll();
    }

    public function getFeedByChannel(Channel $channel)
    {
        return $channel->getFeeds();
    }

    public function createChannel(Channel $channel)
    {
        $em = $this->doctrine->getManager();

        $user = $this->userProvider->getCurrentUser();

        if (!($channelArray = $em->getRepository('CoreBundle:Channel')->findBy(array('url' => $channel->getUrl())))) {
                $em->persist($channel);
                $em->flush();
        }
        else {
            $channel = $channelArray[0];
        }

        $ChannelUser = new ChannelUser();
        $ChannelUser->setChannel($channel);
        $ChannelUser->setUser($user);
        $em->persist($ChannelUser);
        $em->flush();
        
    }

    public function deleteChannel(Channel $channel)
    {
        $em = $this->doctrine->getManager();
        $em->remove($channel);
        $em->flush();
        return 'Channe has been successfully removed';

    }

}