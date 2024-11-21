<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\JoueurRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: JoueurRepository::class)]
#[Assert\Cascade]
class Joueur
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read_joueurs', 'show_joueur'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le nom doit faire au moins 3 caractères', maxMessage: 'Le nom doit faire au plus 255 caractères')]
    #[Groups(['read_joueurs', 'show_joueur', 'new_joueur','read_equipes', 'show_equipe'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le prénom doit faire au moins 3 caractères', maxMessage: 'Le prénom doit faire au plus 255 caractères')]
    #[Groups(['read_joueurs', 'show_joueur', 'new_joueur','read_equipes', 'show_equipe'])]
    private ?string $prenom = null;

    #[ORM\ManyToOne(inversedBy: 'joueurs',cascade: ['persist', 'remove'])]
    #[Groups(['show_joueur'])]
    private ?Equipe $equipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

}
