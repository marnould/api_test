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
    /** @var EntityManagerInterface $em */
    private EntityManagerInterface $em;

    /** @var UserPasswordEncoderInterface $passwordEncoder */
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $user
     * @return string
     */
    public function encryptPassword(User $user)
    {
        return $this->passwordEncoder->encodePassword($user, $user->getPassword());
    }

    /**
     * @param User $user
     */
    public function persistFlush(User $user)
    {
        // @TODO : Passer le persistFlush dans un AbstractManager
        $this->em->persist($user);
        $this->em->flush();
    }
}