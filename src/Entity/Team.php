<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Validator\Team\Team as TeamValidator;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[Gedmo]
#[UniqueEntity(fields: ['name'], message: 'Team with name {{ value }} already exists')]
#[TeamValidator]
class Team implements TraceableErrors
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
    private ?string $name = null;

    #[ORM\Column(length: 5)]
    #[Country(message: 'Wrong country code')]
    #[Groups(['read'])]
    private ?string $countryCode = null;

    #[ORM\Column]
    #[Type(type: 'float')]
    #[Groups(['read'])]
    private ?float $moneyBalance = null;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: PlayerTeam::class, orphanRemoval: true)]
    private Collection $playerTeams;

    #[ORM\OneToOne(mappedBy: 'team', cascade: ['persist', 'remove'])]
    private ?TeamManager $teamManager = null;

    #[ORM\Column(length: 128, unique: true)]
    #[Gedmo\Slug(fields: ['name'])]
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

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
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

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getMoneyBalance(): ?float
    {
        return $this->moneyBalance;
    }

    public function setMoneyBalance(float $moneyBalance): self
    {
        $this->moneyBalance = $moneyBalance;

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
            $playerTeam->setTeam($this);
        }

        return $this;
    }

    public function removePlayerTeam(PlayerTeam $playerTeam): self
    {
        if ($this->playerTeams->removeElement($playerTeam)) {
            // set the owning side to null (unless already changed)
            if ($playerTeam->getTeam() === $this) {
                $playerTeam->setTeam(null);
            }
        }

        return $this;
    }

    public function getTeamManager(): ?TeamManager
    {
        return $this->teamManager;
    }

    public function setTeamManager(?TeamManager $teamManager): self
    {
        // unset the owning side of the relation if necessary
        if ($teamManager === null && $this->teamManager !== null) {
            $this->teamManager->setTeam(null);
        }

        // set the owning side of the relation if necessary
        if ($teamManager !== null && $teamManager->getTeam() !== $this) {
            $teamManager->setTeam($this);
        }

        $this->teamManager = $teamManager;

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
