<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\ProspectRepository;
use App\Repository\RendezVousRepository;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StatisticController
 * @package App\Controller
 * @Route("/api/statistic")
 */
class StatisticController extends AbstractController
{
    /**
     * @Route("/allClients", name="getAllClients")
     */
    public function getHowManyAllContact(ClientRepository $clientRepository, SerializerInterface $serializer): JsonResponse
    {
        $clients = $clientRepository->findAll();
        $response = new JsonResponse();
        $response->setContent(sizeof($clients));
        return $response;
    }

    /**
     * @Route("/clients", name="getClients")
     */
    public function getHowManyContact(): Response
    {
        return new Response(sizeof($this->getUser()->getClients()));
    }

    /**
     * @Route("/prospects", name="getProspects")
     */
    public function getProspect(ProspectRepository $prospectRepository): JsonResponse {
        return new JsonResponse(sizeof($prospectRepository->findAll()));
    }

    /**
     * @Route("/prospectsStat", name="getProspectsStats")
     */
    public function getProspectStat(ProspectRepository $prospectRepository) {
        $prospectHard = $prospectRepository->findBy(['status' => "CHAUD"]);
        $prospectTiede = $prospectRepository->findBy(['status' => "TIEDE"]);
        $prospectFroid = $prospectRepository->findBy(['status' => "FROID"]);

        $array = ["HARD" => sizeof($prospectHard), "TIEDE" => sizeof($prospectTiede), "FROID" => sizeof($prospectFroid)];
        return new JsonResponse($array, Response::HTTP_OK);
    }


}
