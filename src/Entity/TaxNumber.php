<?php

namespace App\Entity;

use App\Repository\TaxNumberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxNumberRepository::class)]
class TaxNumber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column]
    private ?int $percent = null;

    #[ORM\Column(length: 255)]
    private ?string $mask = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPercent(): ?int
    {
        return $this->percent;
    }

    public function setPercent(int $percent): static
    {
        $this->percent = $percent;

        return $this;
    }

    public function getMask(): ?string
    {
        return $this->mask;
    }

    public function setMask(string $mask): static
    {
        $this->mask = $mask;

        return $this;
    }
}
