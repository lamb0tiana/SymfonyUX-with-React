<?php

namespace App\Controller\Api;

use App\Entity\Player;
use App\Entity\PlayerTeam;
use App\Entity\Team;
use App\Entity\TeamManager;
use App\Repository\TeamRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[Route('/teams')]
class TeamController extends BaseApiController
{
    #[Route('/')]
    public function index(Request $request, TeamRepository $repository)
    {
        $limit = $request->query->get('limit', Constant::PER_PAGE);
        $offset = $request->query->get('page', Constant::DEFAULT_PAGINATION_PAGE_OFFSET);
        $data = $repository->getList($limit, $offset);
        return $this->json($data);
    }

    #[Route('/create', name: 'create_team', methods: [Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        return $this->post($request, Team::class);
    }


    private function validateTransfertRequest(Request $request): JsonResponse
    {
        [ $playerId] = array_values($request->attributes->get('_route_params'));


        if (!$request->attributes->get('player')) {
            $contentResponse = ["error" => sprintf('Player with id %d not found', $playerId)];
            return $this->json($contentResponse, Response::HTTP_NOT_FOUND);
        }

        $post = json_decode($request->getContent(), true);

        if (!isset($post['transfert_amount'])) {
            $contentResponse = ['error' => 'Transfert amount required'];
            return $this->json($contentResponse, Response::HTTP_BAD_REQUEST);
        }

        return $this->json($post);
    }

    /**
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    #[Route('/setPlayer/{slug}', name: 'add_player', methods: Request::METHOD_POST)]
    #[Entity("player", expr: "repository.findOneBySlug(slug)")]
    public function addPlayer(#[CurrentUser] ?TeamManager $teamManager, Request $request, ?Player $player = null): JsonResponse
    {
        $validationQuery = $this->validateTransfertRequest($request);
        $team = $teamManager->getTeam();

        if (!$team) {
            return $this->json([['message' => 'You have no team set', 'error_field' =>'team' ]], Response::HTTP_NOT_FOUND);
        }
        $teamRepository = $this->manager->getRepository(Team::class);
        $team = $teamRepository->find($team->getId());

        if ($validationQuery->getStatusCode() !== Response::HTTP_OK) {
            return $this->json(json_decode($validationQuery->getContent()), $validationQuery->getStatusCode());
        }

        $transfertAmount = json_decode($validationQuery->getContent(), true);
        $playerTeam = (new PlayerTeam())->setPlayer($player)->setTeam($team)->setCost($transfertAmount['transfert_amount']);

        try {
            $response = $this->normalizer->normalize($playerTeam, 'json', ['groups' => ['read']]);
            $errors = $this->validator->validate($playerTeam);
            if ($errors->count()>0) {
                $err = [];
                for ($e = 0; $e < $errors->count(); $e++) {
                    $currentError = $errors->get($e);
                    array_push($err, ['message' => $currentError->getMessage(),  'error_field' => $currentError->getPropertyPath()]);
                }

                return $this->json($err, Response::HTTP_BAD_REQUEST);
            }
            $this->manager->persist($playerTeam);
            $this->manager->flush();
            return $this->json($response, Response::HTTP_CREATED);
        } catch (ExceptionInterface $exception) {
            return $this->json(["error" => $exception->getMessage(), "code" => $exception->getCode()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route("/{slug}/players", name: 'get_team_players', methods: Request::METHOD_GET)]
    #[Entity("team", expr: "repository.findOneBySlug(slug)")]
    public function getPlayers(Team $team, TeamRepository $repository): JsonResponse
    {
        $data = $repository->getPlayerList($team);
        return $this->json($data);
    }
}
