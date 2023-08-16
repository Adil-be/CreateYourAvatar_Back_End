<?php

namespace App\Entity;

use App\Entity\Traits\HasIdTraits;
use App\Repository\NftValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NftValueRepository::class)]
class NftValue
{

    use HasIdTraits;

    #[ORM\Column]
    private ?\DateTimeImmutable $valueDate = null;

    #[ORM\Column]
    private ?float $value = null;

    #[ORM\ManyToOne(inversedBy: 'NftValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Nft $nft = null;


    public function getValueDate(): ?\DateTimeImmutable
    {
        return $this->valueDate;
    }

    public function setValueDate(\DateTimeImmutable $valueDate): static
    {
        $this->valueDate = $valueDate;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getNft(): ?Nft
    {
        return $this->nft;
    }

    public function setNft(?Nft $nft): static
    {
        $this->nft = $nft;

        return $this;
    }
}