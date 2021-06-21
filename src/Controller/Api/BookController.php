<?php


namespace App\Controller\Api;

use App\Entity\User;
use App\Manager\BookManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class BookController
 * @package App\Controller\Api
 */
class BookController extends AbstractController
{
    /** @var BookManager $bookManager */
    private BookManager $bookManager;

    /**
     * BookController constructor.
     */
    public function __construct(BookManager $bookManager, SerializerInterface $serializer)
    {
        parent::__construct($serializer);

        $this->bookManager = $bookManager;
    }

    /**
     * @Route(name="api_all_books", path="/books")
     */
    public function getBooks()
    {
        /** @var User $user */
        $user = $this->getUser();

        $booksData = $this->bookManager->getByUser($user);

        // Vanilla API use case (without FosRestBundle)
        $dataResponse = $this->sfSerializer->serialize($booksData, 'json', ['groups' => ['books']]);
        return new Response($dataResponse, Response::HTTP_OK, ['content-type' => "application/json"]);
    }

}