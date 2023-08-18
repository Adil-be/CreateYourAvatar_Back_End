<?php

namespace App\Entity;

use App\Entity\Traits\HasIdTraits;
use App\Entity\Traits\HasNameTrait;
use App\Repository\NftImageRepository;
use Doctrine\ORM\Mapping as ORM;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: NftImageRepository::class)]
class NftImage
{
    use HasIdTraits;
    use HasNameTrait;


    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'nftImages', fileNameProperty: 'name', size: 'size')]
    private ?File $file = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;



    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'nftImages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?NftModel $nftModel = null;

    #[ORM\ManyToOne(inversedBy: 'nftImages')]
    #[ORM\JoinColumn(nullable: false)]



    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }





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

    public function getNftModel(): ?NftModel
    {
        return $this->nftModel;
    }

    public function setNftModel(?NftModel $nftModel): static
    {
        $this->nftModel = $nftModel;

        return $this;
    }
}