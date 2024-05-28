<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $payment_method = null;

    #[ORM\Column(length: 255)]
    private ?string $payment_txid = null;

    #[ORM\Column(length: 20)]
    private ?string $payment_status = null;

    #[ORM\ManyToOne(inversedBy: 'array_payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentMethod(): ?int
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(int $payment_method): static
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getPaymentTxid(): ?string
    {
        return $this->payment_txid;
    }

    public function setPaymentTxid(string $payment_txid): static
    {
        $this->payment_txid = $payment_txid;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->payment_status;
    }

    public function setPaymentStatus(string $payment_status): static
    {
        $this->payment_status = $payment_status;

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
}
