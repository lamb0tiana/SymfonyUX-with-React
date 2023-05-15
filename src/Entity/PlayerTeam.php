<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Repository\PlayerTeamRepository;
use App\Validator\Player\TransfertPlayer as TransfertPlayerValidator;
use ApiPlatform\Metadata\GraphQl\Query;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlayerTeamRepository::class)]
#[UniqueEntity(fields: ['player','team', 'isCurrentTeam'], message: 'This player is already in this team')]
#[ApiResource(graphQlOperations: [
    new Query(),
    new QueryCollection(),
])]
class PlayerTeam
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    #[Groups(['read'])]
    #[TransfertPlayerValidator]
    private ?float $cost = 0;

    #[ORM\ManyToOne(inversedBy: 'playerTeams')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'playerTeams')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read'])]
    private ?Team $team = null;

    #[ORM\Column]
    private ?bool $isCurrentTeam = true;


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

    public function isIsCurrentTeam(): ?bool
    {
        return $this->isCurrentTeam;
    }

    public function setIsCurrentTeam(bool $isCurrentTeam): self
    {
        $this->isCurrentTeam = $isCurrentTeam;

        return $this;
    }


}
