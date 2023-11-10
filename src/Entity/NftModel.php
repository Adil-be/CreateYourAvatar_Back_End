<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
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
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: NftModelRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['NftModel:read']],
    denormalizationContext: ['groups' => ['NftModel:write']],
    operations: [
        new Get(),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ADMIN')"),

    ],
    paginationItemsPerPage: 20,
    paginationClientItemsPerPage: true)]

#[ApiFilter(BooleanFilter::class, properties: ['featured'])]
#[ApiFilter(OrderFilter::class, properties: ['initialPrice', 'createdAt'])]
#[ApiFilter(NumericFilter::class, properties: ['nft.user.id', 'nftCollection.id'])]
#[ApiFilter(RangeFilter::class, properties: ['initialPrice'])]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'description' => 'partial'])]
class NftModel
{
    public function __construct()
    {
        $this->nft = new ArrayCollection();
        $this->nftValues = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->nftImages = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['NftModel:read', 'nft:read:full'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['NftModel:write', 'NftModel:read', 'nft:read:full'])]
    #[Assert\NotBlank]
    private ?string $name = null;


    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups(['NftModel:read', 'nft:read:full'])]
    private ?float $initialPrice = null;

    #[ORM\Column]
    #[Groups(['NftModel:read', 'nft:read:full'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['NftModel:read', 'nft:read:full'])]
    private ?int $quantity = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['NftModel:write', 'NftModel:read', 'nft:read:full'])]
    #[Assert\Length(
        min: 2,
        max: 500,
        minMessage: 'Your description must be at least {{ limit }} characters long',
        maxMessage: 'Your description cannot be longer than {{ limit }} characters',
    )]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'nftModel', targetEntity: Nft::class)]
    #[Groups(['NftModel:read'])]
    private Collection $nft;

    #[ORM\ManyToOne(inversedBy: 'NftModels')]
    #[Groups(['NftModel:read', 'nft:read:full'])]
    private ?NftCollection $nftCollection = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'nftModels')]
    #[Groups(['NftModel:read', 'nft:read:full'])]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'nftModel', targetEntity: NftImage::class)]
    #[Groups(['NftModel:read', 'nft:read:full'])]
    private Collection $nftImages;

    #[ORM\OneToMany(mappedBy: 'nftModel', targetEntity: NftValue::class)]
    #[Groups(['NftModel:read'])]
    private Collection $nftValues;

    #[ORM\Column]
    #[Groups(['NftModel:read'])]
    private ?bool $featured = null;

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
// ....
    public function getInitialPrice(): ?float
    {
        return $this->initialPrice;
    }

    public function setInitialPrice(float $initialPrice): static
    {
        $this->initialPrice = $initialPrice;

        return $this;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
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

    public function isFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): static
    {
        $this->featured = $featured;

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
            $nftValue->setNftModel($this);
        }

        return $this;
    }

    public function removeNftValue(NftValue $nftValue): static
    {
        if ($this->nftValues->removeElement($nftValue)) {
            // set the owning side to null (unless already changed)
            if ($nftValue->getNftModel() === $this) {
                $nftValue->setNftModel(null);
            }
        }

        return $this;
    }

}