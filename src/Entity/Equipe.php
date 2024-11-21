<?php

namespace App\Entity;


use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EquipeRepository::class)]
#[UniqueEntity('nom')]
#[Assert\Cascade]
class Equipe
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read_joueurs', 'new_joueur', 'read_equipes', 'show_equipe', 'show_score'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, max: 255, minMessage: 'Le nom doit faire au moins 3 caractères', maxMessage: 'Le nom doit faire au plus 255 caractères')]
    #[Groups(['read_joueurs', 'show_joueur', 'new_joueur', 'read_equipes', 'show_equipe', 'read_scores', 'show_score', 'new_equipe'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 2, max: 255, minMessage: 'La ville doit faire au moins 2 caractères', maxMessage: 'La ville doit faire au plus 255 caractères')]
    #[Groups(['read_joueurs', 'show_joueur', 'new_joueur', 'read_equipes', 'show_equipe', 'show_score', 'new_equipe'])]
    private ?string $ville = null;

    /**
     * @var Collection<int, Joueur>
     */
    #[ORM\OneToMany(targetEntity: Joueur::class, mappedBy: 'equipe', cascade: ['persist', 'remove'])]
    #[Groups(['show_equipe'])]
    private Collection $joueurs;

    #[ORM\OneToOne(mappedBy: 'equipe', cascade: ['persist', 'remove'])]
    #[Groups(['show_equipe'])]
    private ?Score $score = null;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Joueur>
     */
    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): static
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs->add($joueur);
            $joueur->setEquipe($this);
        }

        return $this;
    }

    public function removeJoueur(Joueur $joueur): static
    {
        if ($this->joueurs->removeElement($joueur)) {
            // set the owning side to null (unless already changed)
            if ($joueur->getEquipe() === $this) {
                $joueur->setEquipe(null);
            }
        }

        return $this;
    }

    public function getScore(): ?Score
    {
        return $this->score;
    }

    public function setScore(Score $score): static
    {
        // set the owning side of the relation if necessary
        if ($score->getEquipe() !== $this) {
            $score->setEquipe($this);
        }

        $this->score = $score;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }
}