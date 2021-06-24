<?php

namespace App\Controller\Api;

use App\Entity\EntityInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

// Bonne pratique : Renommer la class initiale avec Base

/**
 * Class AbstractController
 * @package App\Controller\Api
 */
abstract class AbstractController extends BaseAbstractController
{
    /** @var SerializerInterface $sfSerializer */
    protected SerializerInterface $sfSerializer;

    /**
     * AbstractController constructor.
     * @param SerializerInterface $sfSerializer
     */
    public function __construct(SerializerInterface $sfSerializer)
    {
        $this->sfSerializer = $sfSerializer;
    }

    /**
     * @return Response
     */
    public function createdResponse(EntityInterface $entity)
    {
        return $this->response($entity, Response::HTTP_CREATED, 'success', ['user_details']);
    }

    /**
     * @param EntityInterface $entity
     * @return Response
     */
    public function successResponse(EntityInterface $entity)
    {
        // Code 200 -> Ok
        return $this->response($entity, Response::HTTP_OK, 'success', ['user_details']);
    }

    /**
     * @param $validatorConstraint
     *
     * @return Response
     * @throws Exception
     */
    public function failResponse($validatorConstraint)
    {
        // Code 400 ou 422 -> Probleme de validation
        return $this->response($this->iterateOnConstraintViolations($validatorConstraint), Response::HTTP_BAD_REQUEST, 'failed', ['user_details']);

    }

    /**
     * @param EntityInterface $entity
     * @return Response
     */
    public function errorResponse(EntityInterface $entity)
    {
        // Code range 500groupes
        return $this->response($entity, Response::HTTP_INTERNAL_SERVER_ERROR, 'error', ['user_details']);
    }

    /**
     * @param EntityInterface $entity
     * @return Response
     */
    public function notFoundResponse(EntityInterface $entity)
    {
        // Code 404
        return $this->response($entity, Response::HTTP_NOT_FOUND, 'error', ['user_details']);
    }

    /**
     * @param EntityInterface $entity
     *
     * @return Response
     */
    public function forbiddenResponse(EntityInterface $entity)
    {
        // Code 403
        return $this->response($entity, Response::HTTP_FORBIDDEN, 'error', ['user_details']);
    }

    /**
     * @param $entity
     * @param int $statusCode
     * @param string $status
     * @param array $groups
     *
     * @return Response
     */
    private function response($entity, int $statusCode, string $status, array $groups = []): Response
    {
        return new Response(
            $this->sfSerializer->serialize(
                [
                    'status' => $status,
                    'data' => $entity,
                ]
                ,
                'json', ['groups' => $groups]),
            $statusCode
        );
    }

    /**
     * @param ConstraintViolationList $constraints
     *
     * @return array
     * @throws Exception
     */
    protected function iterateOnConstraintViolations(ConstraintViolationList $constraints): array
    {
        $errorArray = [];

        foreach ($constraints->getIterator() as $constraint) {
           $errorArray[$constraint->getPropertyPath()] =  $constraint->getMessage();
        }

        return $errorArray;
    }
}