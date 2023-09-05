<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\HasIdTraits;
use App\Entity\Traits\HasNameTrait;
use App\Repository\NftCollectionRepository;
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

#[ORM\Entity(repositoryClass: NftCollectionRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['NftCollection:read', 'read']],
    denormalizationContext: ['groups' => ['NftCollection:write', 'write']],
    operations: [
        new Get(),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Put(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ADMIN')"),
    ], )]
class NftCollection
{
    use HasIdTraits;
    use HasNameTrait;

    #[ORM\OneToMany(mappedBy: 'nftCollection', targetEntity: NftModel::class)]
    #[Groups(['NftCollection:read'])]
    private Collection $NftModels;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['NftCollection:write', 'NftCollection:read'])]
    private ?string $path = null;

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }
}