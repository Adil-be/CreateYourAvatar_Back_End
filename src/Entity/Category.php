<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\HasIdTraits;
use App\Entity\Traits\HasNameTrait;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource()]

class Category
{
    use HasIdTraits;
    use HasNameTrait;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[MaxDepth(2)]
    private ?self $parent = null;

    #[ORM\ManyToMany(targetEntity: NftModel::class, inversedBy: 'categories')]
    private Collection $nftModels;

    public function __construct()
    {
        $this->nftModels = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeNftModel(NftModel $nftModel): static
    {
        $this->nftModels->removeElement($nftModel);

        return $this;
    }
}