<?php

namespace App\Entity;

use App\Repository\ArticlePictureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlePictureRepository::class)]
class ArticlePicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $picture_link = null;


    #[ORM\ManyToOne(inversedBy: 'articlePictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Color $color = null;

    #[ORM\ManyToOne(inversedBy: 'articlePictures')]
    private ?Article $article = null;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPictureLink(): ?string
    {
        return $this->picture_link;
    }

    public function setPictureLink(string $picture_link): static
    {
        $this->picture_link = $picture_link;

        return $this;
    }

    public function getColor(): ?color
    {
        return $this->color;
    }

    public function setColor(?color $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }


}
