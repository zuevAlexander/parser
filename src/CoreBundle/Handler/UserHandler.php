<?php

namespace CoreBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use CoreBundle\Entity\User;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class UserHandler
{

    /**
     * @var \Symfony\Bridge\Doctrine\RegistryInterface
     */
    protected $doctrine;

    /**
     * UserHandler constructor.
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $doctrine
     */
    public function __construct(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getUsers()
    {
        $em = $this->doctrine->getManager();

        $users = $em->getRepository('CoreBundle:User')->findAll();
        
        return $users;
    }

    public function createUser(User $user)
    {
        
        $user->setApiKey(md5($user->getUsername().time()));
        $user->setPassword(md5($user->getPassword()));
        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();
        
        return 'User has been saved successfully';

    }

    public function getUser(User $user)
    {
        $em = $this->doctrine->getManager();

        $client = $em->getRepository('CoreBundle:User')
            ->findBy(array('username' => $user->getUsername(), 'password' => md5($user->getPassword())));

        return $client[0];
    }

}