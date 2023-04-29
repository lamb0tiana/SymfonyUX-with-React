<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use App\Validator\CountryCodeValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team implements TraceableErrors
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Length(min: 4, minMessage: 'Too short, min 4')]
    private ?string $name = null;

    #[ORM\Column(length: 5)]
    #[Country(message: 'Wrong country code')]
    private ?string $countryCode = null;

    #[ORM\Column]
    #[Type(type: 'float')]
    private ?float $moneyBalance = null;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: PlayerTeam::class, orphanRemoval: true)]
    private Collection $playerTeams;

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


}
