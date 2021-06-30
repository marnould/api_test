<?php


namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserManager
 * @package App\Manager
 */
class UserManager
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private DoctrineManager $dm;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, DoctrineManager $dm)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->dm = $dm;
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function createUser(User $user) : User
    {
        $user->setPassword($this->encryptPassword($user));
        $this->dm->persistFlush($user);

        return $user;
    }

    /**
     * @param User $user
     * @return string
     */
    public function encryptPassword(User $user)
    {
        return $this->passwordEncoder->encodePassword($user, $user->getPassword());
    }
}
