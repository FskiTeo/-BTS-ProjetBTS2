<?php

namespace App\Entity;

use App\Repository\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColorRepository::class)]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $color_label = null;

    #[ORM\OneToMany(mappedBy: 'color', targetEntity: ArticlePicture::class)]
    private Collection $articlePictures;

    public function __construct()
    {
        $this->articlePictures = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColorLabel(): ?string
    {
        return $this->color_label;
    }

    public function setColorLabel(string $color_label): static
    {
        $this->color_label = $color_label;

        return $this;
    }

    /**
     * @return Collection<int, ArticlePicture>
     */
    public function getArticlePictures(): Collection
    {
        return $this->articlePictures;
    }

    public function addArticlePicture(ArticlePicture $articlePicture): static
    {
        if (!$this->articlePictures->contains($articlePicture)) {
            $this->articlePictures->add($articlePicture);
            $articlePicture->setColor($this);
        }

        return $this;
    }

    public function removeArticlePicture(ArticlePicture $articlePicture): static
    {
        if ($this->articlePictures->removeElement($articlePicture)) {
            // set the owning side to null (unless already changed)
            if ($articlePicture->getColor() === $this) {
                $articlePicture->setColor(null);
            }
        }

        return $this;
    }

}
