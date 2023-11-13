<?php

namespace App\Entity;

use ApiPlatform\Action\PlaceholderAction;
use ApiPlatform\Metadata\ApiResource;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Patch(
            security: "is_granted('ROLE_ADMIN') or object.getId() == user.getId()",
            securityMessage: 'Sorry, but you are not the user.',
            denormalizationContext: ['groups' => ['user:write']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['user:write']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['user:read']]
        ),
        new Get(
            normalizationContext: ['groups' => ['user:read']]
        ),
        new Get(
            name: 'AuthenticatedUser',
            uriTemplate: '/user_auth/{id}',
            controller: PlaceholderAction::class,
            security: "is_granted('ROLE_ADMIN') or object.getId() == user.getId()",
            securityMessage: 'Sorry, but you are not the user.',
            normalizationContext: ['groups' => ['user_auth:read']]
        ),
    ], )]

class User implements UserInterface, PasswordAuthenticatedUserInterface, JWTUserInterface
{
    public function __construct()
    {
        $this->Nfts = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([ 'user:read', 'user_auth:read', 'nft:read:full'])]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:write', 'user:read', 'user_auth:read', 'nft:read:full'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user_auth:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user_auth:write', 'user:read',  'user_auth:read', 'nft:read:full'])]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:write', 'user_auth:read'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:write', 'user_auth:read'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:write', 'user_auth:read'])]
    private ?string $gender = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user:write', 'user_auth:read'])]
    private ?\DateTimeImmutable $birthday = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:write', 'user_auth:read'])]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Nft::class,cascade: ['remove'])]
    #[Groups(['user_auth:read', 'user:read'])]
    private Collection $nfts;

    #[ORM\OneToOne(targetEntity: UserImage::class,cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['user:write', 'user:read', 'user_auth:read', 'nft:read:full'])]
    private ?UserImage $userImage = null;




    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->getEmail();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeImmutable $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection<int, Nft>
     */
    public function getNfts(): Collection
    {
        return $this->nfts;
    }

    public function addNft(Nft $nft): static
    {
        if (!$this->nfts->contains($nft)) {
            $this->nfts->add($nft);
            $nft->setUser($this);
        }

        return $this;
    }

    public function removeNft(Nft $nft): static
    {
        if ($this->nfts->removeElement($nft)) {
            // set the owning side to null (unless already changed)
            if ($nft->getUser() === $this) {
                $nft->setUser(null);
            }
        }

        return $this;
    }

    public function getUserImage(): ?UserImage
    {
        return $this->userImage;
    }

    public function setUserImage(UserImage $userImage): static
    {
        // set the owning side of the relation if necessary
        // if ($userImage->getUser() !== $this) {
        //     $userImage->setUser($this);
        // }

        $this->userImage = $userImage;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public static function createFromPayload($id, array $payload): self
    {
        return (new self())
            ->setId($id)
            ->setRoles($payload['roles'])
            ->setEmail($payload['email'])
        ;
    }
}