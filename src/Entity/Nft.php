<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\HasIdTraits;
use App\Repository\NftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;


#[ORM\Entity(repositoryClass: NftRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read','nft:read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new Get(),
        new Patch(),
        new Put(),
        new Delete(),
        new GetCollection(),
        new Post(),
    ], )]
class Nft
{
    use HasIdTraits;

    #[ORM\Column]
    #[Groups(['write', 'read'])]
    private ?float $buyingPrice = null;

    #[Groups(['write', 'read'])]
    #[ORM\Column(nullable: true)]
    private ?float $sellingPrice = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read'])]
    private ?string $token = null;

    #[ORM\Column]
    #[Groups(['write', 'read'])]
    private ?bool $inSale = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?\DateTimeImmutable $purchaseDate = null;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: NftValue::class)]
    private Collection $NftValues;

    #[ORM\ManyToOne(inversedBy: 'nft')]
    #[Groups(['read','nft:read'])]
    private ?NftModel $nftModel = null;

    #[ORM\ManyToOne(inversedBy: 'Nfts')]
    #[Groups(['read'])]
    private ?User $user = null;



    public function __construct()
    {
        $this->NftValues = new ArrayCollection();
    }


    public function getBuyingPrice(): ?float
    {
        return $this->buyingPrice;
    }

    public function setBuyingPrice(float $buyingPrice): static
    {
        $this->buyingPrice = $buyingPrice;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function isInSale(): ?bool
    {
        return $this->inSale;
    }

    public function setInSale(bool $inSale): static
    {
        $this->inSale = $inSale;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTimeImmutable
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(\DateTimeImmutable $purchaseDate): static
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    /**
     * @return Collection<int, NftValue>
     */
    public function getNftValues(): Collection
    {
        return $this->NftValues;
    }

    public function addNftValue(NftValue $nftValue): static
    {
        if (!$this->NftValues->contains($nftValue)) {
            $this->NftValues->add($nftValue);
            $nftValue->setNft($this);
        }

        return $this;
    }

    public function removeNftValue(NftValue $nftValue): static
    {
        if ($this->NftValues->removeElement($nftValue)) {
            // set the owning side to null (unless already changed)
            if ($nftValue->getNft() === $this) {
                $nftValue->setNft(null);
            }
        }

        return $this;
    }

    public function getNftModel(): ?NftModel
    {
        return $this->nftModel;
    }

    public function setNftModel(?NftModel $nftModel): static
    {
        $this->nftModel = $nftModel;

        return $this;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSellingPrice(): ?float
    {
        return $this->sellingPrice;
    }

    public function setSellingPrice(?float $sellingPrice): static
    {
        $this->sellingPrice = $sellingPrice;

        return $this;
    }
}