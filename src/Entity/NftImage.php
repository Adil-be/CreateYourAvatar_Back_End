<?php

namespace App\Entity;

use App\Entity\Traits\HasIdTraits;
use App\Entity\Traits\HasNameTrait;
use App\Repository\NftImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NftImageRepository::class)]
class NftImage
{
    use HasIdTraits;
    use HasNameTrait;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

 

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'nftImages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Nft $Nft = null;


    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getNft(): ?Nft
    {
        return $this->Nft;
    }

    public function setNft(?Nft $Nft): static
    {
        $this->Nft = $Nft;

        return $this;
    }
}
