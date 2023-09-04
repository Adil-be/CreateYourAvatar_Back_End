<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\HasIdTraits;
use App\Entity\Traits\HasNameTrait;
use App\Repository\NftModelRepository;
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

#[ORM\Entity(repositoryClass: NftModelRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new Get(),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Put(security:"is_granted('ROLE_ADMIN')"),
        new Delete(security:"is_granted('ROLE_ADMIN')"),
        new GetCollection(),
        new Post(security:"is_granted('ROLE_ADMIN')"),
    ], )]
class NftModel
{
    use HasIdTraits;
    use HasNameTrait;

    public function __construct()
    {
        $this->nft = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->nftImages = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable;
    }


    #[ORM\Column(nullable: true)]
    #[Groups(['read'])]
    private ?float $initialPrice = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column(length: 255, nullable: true)]
    // #[Groups(['write', 'read'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'nftModel', targetEntity: Nft::class)]
    private Collection $nft;

    #[ORM\ManyToOne(inversedBy: 'NftModels')]
    private ?NftCollection $nftCollection = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'nftModels')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'nftModel', targetEntity: NftImage::class)]
    private Collection $nftImages;

    #[ORM\ManyToOne(inversedBy: 'nftModels')]


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

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addNftModel($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeNftModel($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, NftImage>
     */
    public function getNftImages(): Collection
    {
        return $this->nftImages;
    }

    public function addNftImage(NftImage $nftImage): static
    {
        if (!$this->nftImages->contains($nftImage)) {
            $this->nftImages->add($nftImage);
            $nftImage->setNftModel($this);
        }

        return $this;
    }

    public function removeNftImage(NftImage $nftImage): static
    {
        if ($this->nftImages->removeElement($nftImage)) {
            // set the owning side to null (unless already changed)
            if ($nftImage->getNftModel() === $this) {
                $nftImage->setNftModel(null);
            }
        }

        return $this;
    }

}