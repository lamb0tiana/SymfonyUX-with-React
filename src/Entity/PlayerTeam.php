<?php

namespace App\Entity;

use App\Repository\PlayerTeamRepository;
use App\Validator\TransfertPlayer;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlayerTeamRepository::class)]
#[UniqueEntity(fields: ['player','team'], message: 'This player is already in this team')]
class PlayerTeam
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    #[Groups(['read'])]
    #[TransfertPlayer]
    private ?float $cost = null;

    #[ORM\ManyToOne(inversedBy: 'playerTeams')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'playerTeams')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?Team $team = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }


}
