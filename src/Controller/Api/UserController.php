<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    private UserManager $userManager;
    private ValidatorInterface $validator;

    /**
     * UserController constructor.
     *
     * @param UserManager $userManager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(UserManager $userManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        parent::__construct($serializer);

        $this->userManager = $userManager;
        $this->validator = $validator;
    }

    /**
     * Create a new user
     *
     * @Route(name="api_create_user", path="/users", methods={"POST"})
     */
    public function createUserAction(Request $request)
    {
        $body = $request->getContent();

        /** @var User $user */
        $user = $this->sfSerializer->deserialize($body, User::class, 'json');

        $validationConstraint = $this->validator->validate($user);

        if ($validationConstraint->count() > 0) {
            return $this->failResponse($validationConstraint);
        }

        $createdUser = $this->userManager->createUser($user);

        return $this->createdResponse($createdUser, ['groups' => ['user_details']]);
    }
}
