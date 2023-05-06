<?php

namespace App\Controller\Api;

use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Security\Voter\PlayerWorthVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/players')]
class PlayerController extends BaseApiController
{
    #[Route('/create')]
    public function create(Request $request): JsonResponse
    {
        return $this->post($request, Player::class);
    }

    #[Route('/{slug}/worth')]
    #[Entity("player", expr: "repository.findOneBySlug(slug)")]
    public function setWorth(Request $request, EntityManagerInterface $entityManager, ?Player $player)
    {
        $this->denyAccessUnlessGranted(PlayerWorthVoter::CAN_SET_WORTH, $player);
        $repository = $entityManager->getRepository(PlayerTeam::class);
        $post = json_decode($request->getContent(), true);
        $currentPlayerTeam = $repository->getTeamOfPlayer($player);

        /** @var PlayerTeam $playerTeam */
        $playerTeam = $currentPlayerTeam->getPlayerTeams()->filter(function (PlayerTeam $e) use ($player) {
            return $e->getPlayer()->getId() === $player->getId();
        })->last();

        ['worth' => $worth] = $post;
        $worth = str_replace([" "], [""], $worth) ;
        if (preg_match("/[^\d]/", $worth)) {
            return $this->json(['Worth is not correct'], Response::HTTP_BAD_REQUEST);
        }
        $playerTeam->setCost($worth);
        $entityManager->flush();
        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
