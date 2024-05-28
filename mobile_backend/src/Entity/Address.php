<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $address_street = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address_street2 = null;

    #[ORM\Column(length: 255)]
    private ?string $address_postal_code = null;

    #[ORM\Column(length: 255)]
    private ?string $address_city = null;

    #[ORM\Column(length: 255)]
    private ?string $address_country = null;

    #[ORM\Column(length: 255)]
    private ?string $address_phone_number = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddressStreet(): ?string
    {
        return $this->address_street;
    }

    public function setAddressStreet(string $address_street): static
    {
        $this->address_street = $address_street;

        return $this;
    }

    public function getAddressStreet2(): ?string
    {
        return $this->address_street2;
    }

    public function setAddressStreet2(?string $address_street2): static
    {
        $this->address_street2 = $address_street2;

        return $this;
    }

    public function getAddressPostalCode(): ?string
    {
        return $this->address_postal_code;
    }

    public function setAddressPostalCode(string $address_postal_code): static
    {
        $this->address_postal_code = $address_postal_code;

        return $this;
    }

    public function getAddressCity(): ?string
    {
        return $this->address_city;
    }

    public function setAddressCity(string $address_city): static
    {
        $this->address_city = $address_city;

        return $this;
    }

    public function getAddressCountry(): ?string
    {
        return $this->address_country;
    }

    public function setAddressCountry(string $address_country): static
    {
        $this->address_country = $address_country;

        return $this;
    }

    public function getAddressPhoneNumber(): ?string
    {
        return $this->address_phone_number;
    }

    public function setAddressPhoneNumber(string $address_phone_number): static
    {
        $this->address_phone_number = $address_phone_number;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client_id): static
    {
        $this->client = $client_id;

        return $this;
    }
}
