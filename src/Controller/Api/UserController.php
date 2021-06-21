<?php


namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /** @var EntityManagerInterface $em */
    private EntityManagerInterface $em;

    /** @var UserPasswordEncoderInterface $passwordEncoder */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * UserController constructor.
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, SerializerInterface $serializer)
    {
        parent::__construct($serializer);

        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Create a new user
     *
     * @Route(name="api_create_user", path="/users", methods={"POST"})
     */
    public function createUser(Request $request)
    {
        //@TODO move entityManager logic in a UserManager
        //@TODO move encryptPassword logic in a UserManager
        $body = $request->getContent();

        /** @var User $user */
        $user = $this->sfSerializer->deserialize($body, User::class, 'json');

        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

        $this->em->persist($user);
        $this->em->flush();

        $this->createdResponse($user);
    }
}