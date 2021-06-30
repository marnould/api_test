<?php


namespace App\Manager;


use Doctrine\ORM\EntityManagerInterface;

class DoctrineManager
{
    /** @var EntityManagerInterface $em */
    private EntityManagerInterface $em;

    /**
     * DoctrineManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param $entity
     */
    public function persistFlush($entity = null)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
