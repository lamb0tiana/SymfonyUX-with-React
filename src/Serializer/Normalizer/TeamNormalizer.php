<?php

namespace App\Serializer\Normalizer;

use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class TeamNormalizer implements NormalizerInterface, NormalizerAwareInterface, SerializerAwareInterface
{
    use NormalizerAwareTrait;
    use SerializerAwareTrait;

    public const TEAM_ALREADY_CALLED = 'TEAM_ALREADY_CALLED';


    public function normalize($object, $format = null, array $context = [])
    {
        $context[self::TEAM_ALREADY_CALLED] = true;
        if ($object instanceof Team) {
            $data = $this->normalizer->normalize($object, $format, $context);
            $data['playersOfTeam'] = $this->getPlayerOfTeam($object, $context)->toArray();
            return $data;
        } elseif ($object instanceof Player) {
            /** @var PlayerTeam|null $playerTeam */
            $playerTeam = $object->getPlayerTeams()->findFirst(fn (int $i, PlayerTeam $playerTeam) => $playerTeam->isIsCurrentTeam());
            if ($playerTeam) {
                $currentTeam = $playerTeam->getTeam();
                $object->currentTeam = $currentTeam;
                $object->setWorth($playerTeam->getCost());
            }
        }
        return  $this->normalizer->normalize($object, $format, $context);
    }

    private function getActivePlayersInTeam(Team $team): Collection
    {
        return $team->getPlayerTeams()->filter(fn (PlayerTeam $playerTeam) => $playerTeam->isIsCurrentTeam());
    }

    private function getPlayerOfTeam(Team $team, array $context): ArrayCollection
    {
        $activePlayers = $this->getActivePlayersInTeam($team);
        $players =  $activePlayers->map(function (PlayerTeam $playerTeam) use ($team) {
            $player =  $playerTeam->getPlayer();
            $player->currentTeam = $team;
            $player->setWorth($playerTeam->getCost());
            return $player;
        });

        $data = $this->normalizer->normalize($players, 'json', ['groups' => ['item:read'], 'ignore_next' => true]);
        return new ArrayCollection($data);
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        // Make sure we're not called twice
        if (isset($context[self::TEAM_ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Team || $data instanceof Player;
    }
}
