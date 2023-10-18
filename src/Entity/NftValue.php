<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Put;
use App\Entity\Traits\HasIdTraits;
use App\Repository\NftValueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;


#[ORM\Entity(repositoryClass: NftValueRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['NftValue:read']],
    denormalizationContext: ['groups' => ['NftValue:write']],
    operations: [
        new Get(),
        // new Put(),
        // new Delete(),
        new GetCollection(),
        // new Post(),
    ], )]
class NftValue
{
    use HasIdTraits;

    #[ORM\Column]
    #[Groups(['NftValue:read'])]
    private ?\DateTimeImmutable $valueDate = null;

    #[ORM\Column]
    #[Groups(['NftValue:read'])]
    private ?float $value = null;

    #[ORM\ManyToOne(inversedBy: 'nftValues')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['NftValue:read'])]
    private ?NftModel $nftModel = null;


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

    public function getNftModel(): ?Nft
    {
        return $this->nftModel;
    }

    public function setNftModel(?NftModel $nftModel): static
    {
        $this->nft = $nftModel;

        return $this;
    }
}