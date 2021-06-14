<?php


namespace App\Controller\Api;

use App\Manager\BookManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class BookController
 * @package App\Controller\Api
 */
class BookController extends AbstractFOSRestController
{
    private BookManager $bookManager;
    private SerializerInterface $sfSerializer;

    /**
     * BookController constructor.
     */
    public function __construct(BookManager $bookManager, SerializerInterface $sfSerializer){
        $this->bookManager = $bookManager;
        $this->sfSerializer = $sfSerializer;
    }

    /**
     * @Route(name="api_all_books", path="/books")
     */
    public function getBooks()
    {
        $booksData = $this->bookManager->findAll();



        // Vanilla API use case (without FosRestBundle)
        //$dataResponse = $this->sfSerializer->serialize($booksData, 'json', ['groups' => ['books']]);
        //return new Response($dataResponse, Response::HTTP_OK, ['content-type'=> "application/json"]);

        $view = $this->view($booksData, 200);

        return $this->handleView($view);

    }

}