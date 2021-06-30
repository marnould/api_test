<?php


namespace App\Manager;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class BookManager
 * @package App\Manager
 */
class BookManager
{
    private BookRepository $bookRepo;
    private DoctrineManager $dm;

    /**
     * BookManager constructor.
     */
    public function __construct(BookRepository $bookRepo, DoctrineManager $dm)
    {
        $this->bookRepo = $bookRepo;
        $this->dm = $dm;
    }

    /**
     * @return Book[]
     */
    public function getAll()
    {
        return $this->bookRepo->findAll();
    }

    /**
     * @param UserInterface $user
     *
     * @return Book[]
     */
    public function getByUser(UserInterface $user) // Reccommandé de toujours passer par des interfaces au lieu de passer par l'implementation
    {
        return $this->bookRepo->findBy(["owner" => $user]);
    }

    /**
     * @param Book $book
     * @param User|null $user
     *
     * @return Book
     */
    public function createBook(Book $book, User $user): Book
    {
        $book->setOwner($user);

        $this->dm->persistFlush($book);

        return $book;
    }
}
