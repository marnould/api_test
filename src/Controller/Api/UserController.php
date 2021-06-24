<?php


namespace App\Controller\Api;

use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /** @var UserManager $userManager */
    private UserManager $userManager;

    /**
     * UserController constructor.
     */
    public function __construct(UserManager $userManager, SerializerInterface $serializer)
    {
        parent::__construct($serializer);

        $this->userManager = $userManager;
    }

    /**
     * Create a new user
     *
     * @Route(name="api_create_user", path="/users", methods={"POST"})
     */
    public function createUser(Request $request)
    {
        $body = $request->getContent();

        /** @var User $user */
        $user = $this->sfSerializer->deserialize($body, User::class, 'json');

        $user->setPassword($this->userManager->encryptPassword($user));

        $this->userManager->persistFlush($user);

        return $this->createdResponse($user);
    }
}