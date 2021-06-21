<?php

namespace App\Controller\Api;

use App\Entity\EntityInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

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
        return new Response(
            $this->sfSerializer->serialize(
                [
                    'status' => 'success',
                    'data' => $entity
                ]
                ,
                'json', ['groups' => ['user_details']]),
            Response::HTTP_CREATED
        );
    }

    public function successResponse()
    {
        // Code 200 -> Ok
    }

    public function failResponse()
    {
        // Code 400 ou 422 -> Probleme de validation
    }

    public function errorResponse()
    {
        // Code range 500groupes
    }

    public function notFoundResponse()
    {
        // Code 404
    }

    public function forbiddenResponse()
    {
        // Code 403
    }

    /**
     * @return Response
     *
     * @Todo Rajouter en param le status code, le status(success, failed, error), options (groupes)
     */
    private function response()
    {
        return new Response(
            $this->sfSerializer->serialize(
                [
                    'status' => 'success',
                    'data' => $entity
                ]
                ,
                'json', ['groups' => ['user_details']]),
            Response::HTTP_CREATED
        );
    }
}