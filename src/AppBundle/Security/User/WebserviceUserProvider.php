<?php
namespace AppBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use CoreBundle\Entity\User;

class WebserviceUserProvider implements UserProviderInterface
{

    /**
     * @var \Symfony\Bridge\Doctrine\RegistryInterface
     */
    protected $doctrine;

    /**
     * @var \CoreBundle\Entity\User
     */
    private $currentUser;

    /**
     * WebserviceUserProvider constructor.
     * @param \Symfony\Bridge\Doctrine\RegistryInterface $doctrine
     */
    public function __construct(\Symfony\Bridge\Doctrine\RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function loadUserByUsername($apiKey)
    {
        $userData = $this->doctrine->getRepository('CoreBundle:User')->findBy(array('apiKey' => $apiKey));

        if ($userData) {

            $this->currentUser = $userData[0];
            return $this->currentUser;
        }
        
        throw new UsernameNotFoundException(
            sprintf('ApiKey "%s" does not exist.', $apiKey)
        );
    }
    
    public function getCurrentUser() {
        return $this->currentUser;
    }
    
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getApiKey());
    }

    public function supportsClass($class)
    {
        return $class === 'CoreBundle\Entity\User';
    }
}