<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
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
    normalizationContext: ['groups' => ['nft:read', 'read']],
    denormalizationContext: ['groups' => ['nft:write', 'write']],
    operations: [
        new Get(),
        new Patch(security: "is_granted('ROLE_ADMIN') or object.user == user"),
        // new Put(security: "is_granted('ROLE_ADMIN') or object.user == user"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.user == user"),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ADMIN')"),
    ],
    paginationItemsPerPage: 20,
    paginationClientItemsPerPage: true
)]


#[ApiFilter(BooleanFilter::class, properties: ['inSale','featured'])]
#[ApiFilter(OrderFilter::class, properties: ['sellingPrice','nftModel.createdAt','purchaseDate'])]
#[ApiFilter(NumericFilter::class, properties: ['user.id'])]
#[ApiFilter(RangeFilter::class, properties: ['sellingPrice'])]
#[ApiFilter(SearchFilter::class, properties: ['nftModel.name' => 'partial', 'nftModel.description' => 'partial'])]
class Nft
{
    use HasIdTraits;

    #[ORM\Column]
    #[Groups(['nft:write', 'nft:read'])]
    private ?float $buyingPrice = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['nft:write', 'nft:read'])]
    private ?float $sellingPrice = null;

    #[ORM\Column(length: 255)]
    #[Groups(['nft:read'])]
    private ?string $token = null;

    #[ORM\Column]
    #[Groups(['nft:write', 'nft:read'])]
    private ?bool $inSale = null;

    #[ORM\Column]
    #[Groups(['nft:read'])]
    private ?\DateTimeImmutable $purchaseDate = null;

    #[ORM\OneToMany(mappedBy: 'nft', targetEntity: NftValue::class)]
    #[Groups(['nft:read'])]
    private Collection $nftValues;

    #[ORM\ManyToOne(inversedBy: 'nft')]
    #[Groups(['nft:read'])]
    private ?NftModel $nftModel = null;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    #[Groups(['nft:read'])]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['nft:write', 'nft:read'])]
    private ?bool $featured = null;



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
        return $this->nftValues;
    }

    public function addNftValue(NftValue $nftValue): static
    {
        if (!$this->nftValues->contains($nftValue)) {
            $this->nftValues->add($nftValue);
            $nftValue->setNft($this);
        }

        return $this;
    }

    public function removeNftValue(NftValue $nftValue): static
    {
        if ($this->nftValues->removeElement($nftValue)) {
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

    public function isFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): static
    {
        $this->featured = $featured;

        return $this;
    }
}