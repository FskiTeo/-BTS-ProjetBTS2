<?php

namespace App\Entity;

use App\Repository\CarrierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarrierRepository::class)]
class Carrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $carrier_name = null;

    #[ORM\OneToMany(mappedBy: 'carrier_id', targetEntity: Shipping::class)]
    private Collection $array_shipments;

    public function __construct()
    {
        $this->array_shipments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrierName(): ?string
    {
        return $this->carrier_name;
    }

    public function setCarrierName(string $carrier_name): static
    {
        $this->carrier_name = $carrier_name;

        return $this;
    }

    /**
     * @return Collection<int, Shipping>
     */
    public function getArrayShipments(): Collection
    {
        return $this->array_shipments;
    }

    public function addArrayShipment(Shipping $arrayShipment): static
    {
        if (!$this->array_shipments->contains($arrayShipment)) {
            $this->array_shipments->add($arrayShipment);
            $arrayShipment->setCarrierId($this);
        }

        return $this;
    }

    public function removeArrayShipment(Shipping $arrayShipment): static
    {
        if ($this->array_shipments->removeElement($arrayShipment)) {
            // set the owning side to null (unless already changed)
            if ($arrayShipment->getCarrierId() === $this) {
                $arrayShipment->setCarrierId(null);
            }
        }

        return $this;
    }
}
