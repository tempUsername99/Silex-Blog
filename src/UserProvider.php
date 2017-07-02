<?php
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Created by PhpStorm.
 * User: zero0day
 * Date: 12.05.2017
 * Time: 21:01
 */
class UserProvider implements UserProviderInterface
{
    private $dm;

    public function __construct(\Doctrine\ODM\MongoDB\DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function loadUserByUsername($username)
    {
        $temp = $this->dm->getRepository('Documents\User')->findOneBy(array('login' => $username));
        if (count($temp))
        return new User($temp->getLogin(), $temp->getPassword(), array($temp->getRole()), true, true, true, true);
        else throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new Exception(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}