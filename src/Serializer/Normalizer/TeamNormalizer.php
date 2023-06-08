<?php

namespace App\Serializer\Normalizer;

use App\Entity\PlayerTeam;
use App\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TeamNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'BOOK_ATTRIBUTE_NORMALIZER_ALREADY_CALLED';


    public function normalize($object, $format = null, array $context = [])
    {


        $context[self::ALREADY_CALLED] = true;

        $ppp = $this->normalizer->normalize($object, $format, $context);
        $ppp['playersOfTeam'] = $this->getPlayerOfTeam($object)->toArray();
        return $ppp;
    }

    private function getActivePlayersInTeam(Team $team): Collection
    {
        return $team->getPlayerTeams()->filter(fn (PlayerTeam $playerTeam) => $playerTeam->isIsCurrentTeam());
    }

    private function getPlayerOfTeam(Team $team): ArrayCollection
    {
        $activePlayers = $this->getActivePlayersInTeam($team);
        return $activePlayers->map(function (PlayerTeam $playerTeam) use ($team) {
            $player =  $playerTeam->getPlayer();
            $player->currentTeam = $team;
            return $player;
        });
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
