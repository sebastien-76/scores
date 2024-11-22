<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ScoreRepository;
use Symfony\Component\Serializer\Attribute\Groups;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_equipe', 'read_scores', 'show_score'])]
    private ?int $points = null;

    #[ORM\Column]
    #[Groups(['read_equipes','show_equipe', 'read_scores', 'show_score'])]

    private ?int $victoire = null;

    #[ORM\Column]
    #[Groups(['show_equipe', 'read_scores', 'show_score'])]

    private ?int $nul = null;

    #[ORM\Column]
    #[Groups(['show_equipe', 'read_scores', 'show_score'])]
    private ?int $defaite = null;

    #[ORM\OneToOne(inversedBy: 'score', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show_score', 'read_scores'])]
    private ?Equipe $equipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(Equipe $equipe): static
    {
        $this->equipe = $equipe;

        return $this;
    }

    public function getVictoire(): ?int
    {
        return $this->victoire;
    }

    public function setVictoire(int $victoire): static
    {
        $this->victoire = $victoire;

        return $this;
    }

    public function getNul(): ?int
    {
        return $this->nul;
    }

    public function setNul(int $nul): static
    {
        $this->nul = $nul;

        return $this;
    }

    public function getDefaite(): ?int
    {
        return $this->defaite;
    }

    public function setDefaite(int $defaite): static
    {
        $this->defaite = $defaite;

        return $this;
    }

}
