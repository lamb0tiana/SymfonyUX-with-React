<?php

namespace App\Serializer\Normalizer;

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

    private const ALREADY_CALLED = 'ALREADY_CALLED';


    public function normalize($object, $format = null, array $context = [])
    {


        $context[self::ALREADY_CALLED] = true;

        $data = $this->normalizer->normalize($object, $format, $context);
        $data['playersOfTeam'] = $this->getPlayerOfTeam($object)->toArray();
        return $data;
    }

    private function getActivePlayersInTeam(Team $team): Collection
    {
        return $team->getPlayerTeams()->filter(fn (PlayerTeam $playerTeam) => $playerTeam->isIsCurrentTeam());
    }

    private function getPlayerOfTeam(Team $team): ArrayCollection
    {
        $activePlayers = $this->getActivePlayersInTeam($team);
        $players =  $activePlayers->map(function (PlayerTeam $playerTeam) use ($team) {
            $player =  $playerTeam->getPlayer();
            $player->currentTeam = $team;
            $player->setWorth($playerTeam->getCost());
            return $player;
        });

        $data = $this->normalizer->normalize($players, 'json', ['groups' => ['item:read']]);
        return new ArrayCollection($data);
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        // Make sure we're not called twice
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Team;
    }


}
