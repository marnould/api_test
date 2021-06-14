<?php


namespace App\Manager;

use App\Entity\Book;
use App\Repository\BookRepository;

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
    public function findAll()
    {
        return $this->bookRepo->findAll();
    }
}