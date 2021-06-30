<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @UniqueEntity({"title"})
 */
class Book implements EntityInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("books", "book_details")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("books", "book_details")
     *
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     * @Assert\Regex("/^[\d\w_\-]+$/", message="You only can use the _ and the - as special character")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("books", "book_details")
     * @Assert\Length (min=10, max=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="books")
     * @Groups("books")
     */
    private $owner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
