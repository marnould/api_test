<?php


namespace App\Manager;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class BookManager
 * @package App\Manager
 */
class BookManager
{
    private BookRepository $bookRepo;

    /**
     * BookManager constructor.
     */
    public function __construct(BookRepository $bookRepo)
    {
        $this->bookRepo = $bookRepo;
    }

    /**
     * @return Book[]
     */
    public function getAll()
    {
        return $this->bookRepo->findAll();
    }

    /**
     * getByUser
     */
    public function getByUser(UserInterface $user) // Reccommandé de toujours passer par des interfaces au lieu de passer par l'implementation
    {
        return $this->bookRepo->findBy(["owner" => $user]);
    }
}