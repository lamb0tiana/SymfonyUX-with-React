<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\GraphQl\Input\CreatePlayerInput;
use App\Repository\PlayerRepository;
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
    new Mutation(name: 'create')
])]
#[UniqueEntity(fields: ['name', 'surname'], message: 'This already exists')]
class Player implements TraceableErrors
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Length(min: 4, minMessage: 'Too short, min 4')]
    #[Groups(['read'])]
    private string $name;

    #[ORM\Column(length: 100)]
    #[Groups(['read'])]
    private ?string $surname = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: PlayerTeam::class, orphanRemoval: true)]
//    #[ApiResource]
    private Collection $playerTeams;

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['name', 'surname'])]
    #[Groups(['read'])]
    private ?string $slug = null;

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
