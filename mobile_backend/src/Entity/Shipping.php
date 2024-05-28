<?php

namespace App\Entity;

use App\Repository\ShippingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShippingRepository::class)]
class Shipping
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $shipping_tracking_number = null;

    #[ORM\ManyToOne(inversedBy: 'array_shippings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order_id = null;

    #[ORM\ManyToOne(inversedBy: 'array_shipments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carrier $carrier_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShippingTrackingNumber(): ?string
    {
        return $this->shipping_tracking_number;
    }

    public function setShippingTrackingNumber(string $shipping_tracking_number): static
    {
        $this->shipping_tracking_number = $shipping_tracking_number;

        return $this;
    }

    public function getOrderId(): ?Order
    {
        return $this->order_id;
    }

    public function setOrderId(?Order $order_id): static
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getCarrierId(): ?Carrier
    {
        return $this->carrier_id;
    }

    public function setCarrierId(?Carrier $carrier_id): static
    {
        $this->carrier_id = $carrier_id;

        return $this;
    }
}
