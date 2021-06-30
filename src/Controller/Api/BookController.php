<?php


namespace App\Controller\Api;

use App\Entity\Book;
use App\Entity\User;
use App\Manager\BookManager;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BookController
 * @package App\Controller\Api
 */
class BookController extends AbstractController
{
    private BookManager $bookManager;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    /**
     * BookController constructor.
     *
     * @param BookManager $bookManager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(BookManager $bookManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        parent::__construct($serializer);

        $this->bookManager = $bookManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route(name="api_all_books", path="/books", methods={"GET"})
     *
     * @return Response
     */
    public function getBooksAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        $booksData = $this->bookManager->getByUser($user);

        // Vanilla API use case (without FosRestBundle)
        $dataResponse = $this->sfSerializer->serialize($booksData, 'json', ['groups' => ['books']]);
        return new Response($dataResponse, Response::HTTP_OK, ['content-type' => "application/json"]);
    }

    /**
     * @Route (name="create_book", path="/books", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     * @throws Exception
     */
    public function createBookAction(Request $request)
    {
        $body = $request->getContent();

        /** @var Book $book */
        $book = $this->sfSerializer->deserialize($body, Book::class, 'json');

        $validationConstraint = $this->validator->validate($book);

        if ($validationConstraint->count() > 0) {
            return $this->failResponse($validationConstraint);
        }

        /** @var User $user */
        $user = $this->getUser();

        $createdBook = $this->bookManager->createBook($book, $user);

        return $this->createdResponse($createdBook, ['groups' => ['book_details']]);
    }

}
