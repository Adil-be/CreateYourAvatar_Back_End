<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Traits\HasIdTraits;
use App\Entity\Traits\HasNameTrait;
use App\Repository\NftCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Controller\PostImageCollectionController;



#[ORM\Entity(repositoryClass: NftCollectionRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['NftCollection:read', 'read']],
        ),
        new Patch(
            security: "is_granted('ROLE_ADMIN')",
            denormalizationContext: ['groups' => ['NftCollection:write', 'write']]
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')"
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['NftCollection:read', 'read']],
        ),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Post(
            name: 'nft_collections_image',
            uriTemplate: '/nft_collections/{id}/image',
            controller: PostImageCollectionController::class,
            deserialize: false
        ),
    ], )]
#[Vich\Uploadable]
class NftCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['NftCollection:read', 'nft:read:full'])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(length: 255)]
    #[Groups(['NftCollection:write', 'NftCollection:read', 'nft:read:full'])]
    private ?string $name = null;
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function __construct()
    {
        $this->NftModels = new ArrayCollection();
        $this->updatedAt = new \DateTimeImmutable();
    }


    #[ORM\OneToMany(mappedBy: 'nftCollection', targetEntity: NftModel::class)]
    #[Groups(['NftCollection:read'])]
    private Collection $NftModels;


    #[Groups(['NftCollection:write', 'NftCollection:read', 'nft:read:full'])]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    #[Groups(['NftCollection:write', 'NftCollection:read', 'nft:read:full'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[Vich\UploadableField(mapping: 'collectionImages', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $file = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $file
     */
    public function setFile(?File $imageFile = null): void
    {
        $this->file = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

}