<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait HasIdTraits
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
 
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}