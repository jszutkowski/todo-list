<?php

namespace App\Entity;

use App\Embeddable\Book;
use App\Embeddable\Movie;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @Assert\NotBlank
     * @ORM\Column(name="name", type="string", length=255 )
     */
    private $name;

    /**
     *
     * @ORM\Column(name="is_complete", type="boolean")
     */
    private $isComplete = false;

    /**
     *
     * @ORM\Column(name="weight", type="integer")
     */
    private $weight;

    /**
     *
     * @ORM\Column(name="category", type="integer")
     */
    private $category;

    /**
     * @var TodoList
     * @ORM\ManyToOne(targetEntity="App\Entity\TodoList", inversedBy="items",cascade={"persist"})
     * @ORM\JoinColumn(name="list_id", referencedColumnName="id", nullable=false)
     */
    private $list;

    /** @ORM\Embedded(class = "App\Embeddable\Book", columnPrefix="book_") */
    private $bookDetails;

    /** @ORM\Embedded(class = "App\Embeddable\Movie", columnPrefix="movie_") */
    private $movieDetails;

    public function __construct()
    {
        $this->bookDetails = new Book();
        $this->movieDetails = new Movie();
    }

    public function getBookDetails(): Book
    {
        return $this->bookDetails;
    }

    public function setBookDetails(Book $bookDetails): self
    {
        $this->bookDetails = $bookDetails;

        return $this;
    }

    public function getMovieDetails(): Movie
    {
        return $this->movieDetails;
    }

    public function setMovieDetails(Movie $movieDetails): self
    {
        $this->movieDetails = $movieDetails;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsComplete(): ?bool
    {
        return $this->isComplete;
    }

    public function setIsComplete(bool $isComplete): self
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getList(): ?TodoList
    {
        return $this->list;
    }

    public function setList(TodoList $list): self
    {
        $this->list = $list;

        return $this;
    }

}
