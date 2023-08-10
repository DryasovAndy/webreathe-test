<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MeasurementUnitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementUnitRepository::class)]
class MeasurementUnit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    public function __toString() {
        return $this->name . ' (' . $this->getDesignation(). ')';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }
}
