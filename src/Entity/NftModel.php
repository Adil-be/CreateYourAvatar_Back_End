<?php

namespace App\Entity;

use App\Entity\Traits\HasIdTraits;
use App\Entity\Traits\HasNameTrait;
use App\Repository\NftModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NftModelRepository::class)]
class NftModel
{

    
    use HasIdTraits;
    use HasNameTrait;

    #[ORM\Column]
    private ?float $initialPrice = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;


    #[ORM\OneToMany(mappedBy: 'nftModel', targetEntity: Nft::class)]
    private Collection $nft;

    #[ORM\ManyToOne(inversedBy: 'NftModels')]
    private ?NftCollection $nftCollection = null;

    #[ORM\ManyToOne(inversedBy: 'nftModels')]
    private ?NftCategory $nftCategory = null;

    public function __construct()
    {
        $this->nft = new ArrayCollection();
    }



    public function getInitialPrice(): ?float
    {
        return $this->initialPrice;
    }

    public function setInitialPrice(float $initialPrice): static
    {
        $this->initialPrice = $initialPrice;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Nft>
     */
    public function getNft(): Collection
    {
        return $this->nft;
    }

    public function addNft(Nft $nft): static
    {
        if (!$this->nft->contains($nft)) {
            $this->nft->add($nft);
            $nft->setNftModel($this);
        }

        return $this;
    }

    public function removeNft(Nft $nft): static
    {
        if ($this->nft->removeElement($nft)) {
            // set the owning side to null (unless already changed)
            if ($nft->getNftModel() === $this) {
                $nft->setNftModel(null);
            }
        }

        return $this;
    }

    public function getNftCollection(): ?NftCollection
    {
        return $this->nftCollection;
    }

    public function setNftCollection(?NftCollection $nftCollection): static
    {
        $this->nftCollection = $nftCollection;

        return $this;
    }

    public function getNftCategory(): ?NftCategory
    {
        return $this->nftCategory;
    }

    public function setNftCategory(?NftCategory $nftCategory): static
    {
        $this->nftCategory = $nftCategory;

        return $this;
    }
}
