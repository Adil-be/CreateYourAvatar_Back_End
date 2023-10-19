<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\PostImageUserController;
use ApiPlatform\OpenApi\Model;

#[ORM\Entity(repositoryClass: UserImageRepository::class)]
#[ApiResource(operations: [
    new Get(normalizationContext: ['groups' => ['image:read']]),
    new Post(
        name: 'imageUser',
        uriTemplate: '/image/user/{id}',
        controller: PostImageUserController::class,
        deserialize: false,
        validationContext: ['groups' => ['user_image_post']],
        openapi: new Model\Operation(
            requestBody: new Model\RequestBody(
                content: new \ArrayObject([
                    'multipart/form-data' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'file' => [
                                    'type' => 'string',
                                    'format' => 'binary'
                                ]
                            ]
                        ]
                    ]
                ])
            )
        )
    )
])]

#[Vich\Uploadable]
class UserImage
{

    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'nft:read:full', 'user:collection:get'])]
    private ?int $id = null;


    #[Groups(['user:read', 'nft:read:full', 'user:collection:get', 'user_auth:read'])]
    private ?string $path = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    // #[ORM\OneToOne(inversedBy: 'userImage', cascade: ['persist', 'remove'])]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?User $user = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'userImages', fileNameProperty: 'name', size: 'size')]
    #[Assert\NotNull(groups: ['user_image_post'])]
    private ?File $file = null;

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


    public function getId(): ?int
    {
        return $this->id;
    }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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

    // public function getUser(): ?User
    // {
    //     return $this->user;
    // }

    // public function setUser(User $user): static
    // {
    //     $this->user = $user;

    //     return $this;
    // }
}