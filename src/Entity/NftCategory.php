<?php

namespace App\Entity;

use App\Entity\Traits\HasIdTraits;
use App\Entity\Traits\HasNameTrait;
use App\Repository\NftCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NftCategoryRepository::class)]
class NftCategory
{
    use HasIdTraits;
    use HasNameTrait;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\OneToMany(mappedBy: 'nftCategory', targetEntity: NftModel::class)]
    private Collection $nftModels;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private ?self $parent = null;

    public function __construct()
    {
        $this->nftModels = new ArrayCollection();
    }


    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * @return Collection<int, NftModel>
     */
    public function getNftModels(): Collection
    {
        return $this->nftModels;
    }

    public function addNftModel(NftModel $nftModel): static
    {
        if (!$this->nftModels->contains($nftModel)) {
            $this->nftModels->add($nftModel);
            $nftModel->setNftCategory($this);
        }

        return $this;
    }

    public function removeNftModel(NftModel $nftModel): static
    {
        if ($this->nftModels->removeElement($nftModel)) {
            // set the owning side to null (unless already changed)
            if ($nftModel->getNftCategory() === $this) {
                $nftModel->setNftCategory(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }
}