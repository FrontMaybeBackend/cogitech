<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Company
{


    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $catchPhrase = null;

    #[ORM\Column(length: 255)]
    private ?string $bs = null;



    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCatchPhrase(): ?string
    {
        return $this->catchPhrase;
    }

    public function setCatchPhrase(string $catchPhrase): static
    {
        $this->catchPhrase = $catchPhrase;

        return $this;
    }

    public function getBs(): ?string
    {
        return $this->bs;
    }

    public function setBs(string $bs): static
    {
        $this->bs = $bs;

        return $this;
    }
}
