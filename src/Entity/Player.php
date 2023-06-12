<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use App\Repository\PlayerRepository;
use App\Resolver\PlayerMutation;
use App\Security\Voter\PlayerWorthVoter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\GraphQl\Query;
#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[Gedmo]
#[ApiResource(graphQlOperations: [
    new Query(),
    new QueryCollection(),
    new Mutation(name: 'create', denormalizationContext: ['groups' => ['post'] ], normalizationContext: ['groups' => ['read']], security: "is_granted('ROLE_USER')"),
    new Mutation(name: "updateWorth", args: ["id" => ['type' => 'ID!'] ,"worth" => ['type' => 'Float!']], resolver: PlayerMutation::class, security: "is_granted('".PlayerWorthVoter::CAN_SET_WORTH."', object)"),
])
]
#[UniqueEntity(fields: ['name', 'surname'], message: 'already exists', errorPath: 'Player')]

class Player implements TraceableErrors
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read', 'item:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Length(min: 4, minMessage: 'Too short, min 4')]
    #[Groups(['read', 'post', 'item:read'])]
    private string $name;

    #[ORM\Column(length: 100)]
    #[Groups(['read', 'post', 'item:read'])]
    private ?string $surname = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: PlayerTeam::class, orphanRemoval: true)]
    #[Groups(['read'])]
    private Collection $playerTeams;

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['name', 'surname'])]
    #[Groups(['read', 'item:read'])]
    private ?string $slug = null;

    #[ApiProperty(readable: true)]
    #[Groups('item:read')]
    private float $worth;

    #[ApiProperty(readable: true)]
    public ?Team $currentTeam;

    public function setWorth(float $worth = null): self{
        if($worth){
            $this->worth = $worth;
        }
        return $this;
    }

    public function getWorth() : float {
        return $this->worth;
    }

    public function __construct()
    {
        $this->playerTeams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection<int, PlayerTeam>
     */
    public function getPlayerTeams(): Collection
    {
        return $this->playerTeams;
    }

    public function addPlayerTeam(PlayerTeam $playerTeam): self
    {
        if (!$this->playerTeams->contains($playerTeam)) {
            $this->playerTeams->add($playerTeam);
            $playerTeam->setPlayer($this);
        }

        return $this;
    }

    public function removePlayerTeam(PlayerTeam $playerTeam): self
    {
        if ($this->playerTeams->removeElement($playerTeam)) {
            // set the owning side to null (unless already changed)
            if ($playerTeam->getPlayer() === $this) {
                $playerTeam->setPlayer(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
