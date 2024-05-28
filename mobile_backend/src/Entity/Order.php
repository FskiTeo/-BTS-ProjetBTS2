<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $order_datetime = null;

    #[ORM\Column]
    private ?int $order_status = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client_id = null;

    #[ORM\OneToMany(mappedBy: 'order_id', targetEntity: Payment::class)]
    private Collection $array_payments;

    #[ORM\OneToMany(mappedBy: 'order_id', targetEntity: Shipping::class)]
    private Collection $array_shippings;

    #[ORM\OneToMany(mappedBy: 'orderA', targetEntity: OrderDetails::class, orphanRemoval: true)]
    private Collection $orderDetails;

    public function __construct()
    {
        $this->array_payments = new ArrayCollection();
        $this->array_shippings = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDatetime(): ?\DateTimeInterface
    {
        return $this->order_datetime;
    }

    public function setOrderDatetime(\DateTimeInterface $order_datetime): static
    {
        $this->order_datetime = $order_datetime;

        return $this;
    }

    public function getOrderStatus(): ?int
    {
        return $this->order_status;
    }

    public function setOrderStatus(int $order_status): static
    {
        $this->order_status = $order_status;

        return $this;
    }

    public function getClientId(): ?Client
    {
        return $this->client_id;
    }

    public function setClientId(?Client $client_id): static
    {
        $this->client_id = $client_id;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getArrayPayments(): Collection
    {
        return $this->array_payments;
    }

    public function addArrayPayment(Payment $arrayPayment): static
    {
        if (!$this->array_payments->contains($arrayPayment)) {
            $this->array_payments->add($arrayPayment);
            $arrayPayment->setOrderId($this);
        }

        return $this;
    }

    public function removeArrayPayment(Payment $arrayPayment): static
    {
        if ($this->array_payments->removeElement($arrayPayment)) {
            // set the owning side to null (unless already changed)
            if ($arrayPayment->getOrderId() === $this) {
                $arrayPayment->setOrderId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Shipping>
     */
    public function getArrayShippings(): Collection
    {
        return $this->array_shippings;
    }

    public function addArrayShipping(Shipping $arrayShipping): static
    {
        if (!$this->array_shippings->contains($arrayShipping)) {
            $this->array_shippings->add($arrayShipping);
            $arrayShipping->setOrderId($this);
        }

        return $this;
    }

    public function removeArrayShipping(Shipping $arrayShipping): static
    {
        if ($this->array_shippings->removeElement($arrayShipping)) {
            // set the owning side to null (unless already changed)
            if ($arrayShipping->getOrderId() === $this) {
                $arrayShipping->setOrderId(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, OrderDetails>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): static
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setOrderA($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getOrderA() === $this) {
                $orderDetail->setOrderA(null);
            }
        }

        return $this;
    }
}
