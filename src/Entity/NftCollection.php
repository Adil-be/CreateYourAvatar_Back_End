<?php

namespace App\Entity;

use App\Entity\Traits\HasIdTraits;
use App\Entity\Traits\HasNameTrait;
use App\Repository\NftCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NftCollectionRepository::class)]
class NftCollection
{
    use HasIdTraits;
    use HasNameTrait;

    #[ORM\OneToMany(mappedBy: 'nftCollection', targetEntity: NftModel::class)]
    private Collection $NftModels;

    public function __construct()
    {
        $this->NftModels = new ArrayCollection();
    }





    /**
     * @return Collection<int, NftModel>
     */
    public function getNftModels(): Collection
    {
        return $this->NftModels;
    }

    public function addNftModel(NftModel $nftModel): static
    {
        if (!$this->NftModels->contains($nftModel)) {
            $this->NftModels->add($nftModel);
            $nftModel->setNftCollection($this);
        }

        return $this;
    }

    public function removeNftModel(NftModel $nftModel): static
    {
        if ($this->NftModels->removeElement($nftModel)) {
            // set the owning side to null (unless already changed)
            if ($nftModel->getNftCollection() === $this) {
                $nftModel->setNftCollection(null);
            }
        }

        return $this;
    }
}