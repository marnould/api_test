<?php

namespace App\Controller\Api;

use App\Entity\EntityInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

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
    public function createdResponse(EntityInterface $entity, array $options = [])
    {
        return $this->response($entity, Response::HTTP_CREATED, 'success', $options);
    }

    /**
     * @param EntityInterface $entity
     * @return Response
     */
    public function successResponse(EntityInterface $entity, array $options = [])
    {
        // Code 200 -> Ok
        return $this->response($entity, Response::HTTP_OK, 'success', $options);
    }

    /**
     * @param $validatorConstraint
     *
     * @return Response
     * @throws Exception
     */
    public function failResponse($validatorConstraint, array $options = [])
    {
        // Code 400 ou 422 -> Probleme de validation
        return $this->response($this->iterateOnConstraintViolations($validatorConstraint), Response::HTTP_BAD_REQUEST, 'failed', $options);

    }

    /**
     * @param EntityInterface $entity
     * @return Response
     */
    public function errorResponse(EntityInterface $entity, array $options = [])
    {
        // Code range 500
        return $this->response($entity, Response::HTTP_INTERNAL_SERVER_ERROR, 'error', $options);
    }

    /**
     * @param EntityInterface $entity
     * @return Response
     */
    public function notFoundResponse(EntityInterface $entity, array $options = [])
    {
        // Code 404
        return $this->response($entity, Response::HTTP_NOT_FOUND, 'error', $options);
    }

    /**
     * @param EntityInterface $entity
     *
     * @return Response
     */
    public function forbiddenResponse(EntityInterface $entity, array $options = [])
    {
        // Code 403
        return $this->response($entity, Response::HTTP_FORBIDDEN, 'error', $options);
    }

    /**
     * @param $entity
     * @param int $statusCode
     * @param string $status
     * @param array $options
     *
     * @return Response
     */
    private function response($entity, int $statusCode, string $status, array $options = []): Response
    {
        return new Response(
            $this->sfSerializer->serialize(
                [
                    'status' => $status,
                    'data' => $entity,
                ]
                ,
                'json',
                $options),
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
            $errorArray[$constraint->getPropertyPath()] = $constraint->getMessage();
        }

        return $errorArray;
    }
}
