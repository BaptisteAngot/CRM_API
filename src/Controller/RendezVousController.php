<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Entity\Client;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\ProspectRepository;
use App\Entity\Prospect;
use App\Entity\User;
use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
/**
 * Class ClientController
 * @package App\Controller
 * @Route("/api/rendezvous")
 */
class RendezVousController extends AbstractController
{
    /**
     * @Route("/create", name="create_rendez_vous", methods={"POST"})
     * @param UserRepository $userRepository
     * @param ClientRepository $clientRepository
     * @param ProspectRepository $prospectRepository
     * @param RendezVousRepository $rendezVousRepository
     */
    public function createRendezVous(Request $request,ClientRepository $clientRepository, ProspectRepository $prospectRepository ,UserRepository $userRepository, RendezVousRepository $rendezVousRepository)
        {
            $entityManager = $this->getDoctrine()->getManager();

            $newRendezVous = new RendezVous();

            $data = json_decode(
                $request->getContent(),
                true
            );
            $userIdHost = $userRepository->find($data['userIdHost']);
            if ($userIdHost === null ) {
                $response->setContent("Cet utilisateur n'existe pas");
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            } elseif(isset($data['clientId'])) {
                if($clientId = $clientRepository->find($data['clientId'])) {
                    
                    $newRendezVous->setDateStart(new \DateTime($data["dateStart"]))
                    ->setDateEnd(new \DateTime($data["dateEnd"]))
                    ->setDescription($data["description"])
                    ->setUserIdHost($userIdHost);
                    isset($clientId) &&  $newRendezVous->setClientId($clientId);
                    isset($data["invitedMail"]) &&  $newRendezVous->setInvitedMail($data["invitedMail"]);
        
                $entityManager->persist($newRendezVous);
                $entityManager->flush();
        
                $response = new Response();
                $response->setContent('Saved new commune with id ' . $newRendezVous->getId() );
                }
            }elseif(isset($data['ProspectId'])) {
                if($ProspectId = $prospectRepository->find($data['ProspectId'])) {
                    
                    $newRendezVous->setDateStart(new \DateTime($data["dateStart"]))
                    ->setDateEnd(new \DateTime($data["dateEnd"]))
                    ->setDescription($data["description"])
                    ->setUserIdHost($userIdHost);
                    isset($ProspectId) &&  $newRendezVous->setProspectId($ProspectId);
                    isset($data["invitedMail"]) &&  $newRendezVous->setInvitedMail($data["invitedMail"]);
    
        
                $entityManager->persist($newRendezVous);
                $entityManager->flush();
        
                $response = new Response();
                $response->setContent('Saved new commune with id ' . $newRendezVous->getId() );
                }

            }
            return $response;

    }

    /**
     * @Route("/getrendezvous", name="GET_rendez_vous", methods={"GET"})
     * @param RendezVousRepository $rendezVousRepository
     */
    public function getRendezVousByUser(Request $request,RendezVousRepository $rendezVousRepository,SerializerInterface $serializer)
    {
        $user = $this->getUser();
        $rendezVous = $rendezVousRepository->findBy(['userIdHost' => $user->getId()]);

        return JsonResponse::fromJsonString($serializer->serialize($rendezVous, 'json'), Response::HTTP_OK);
     
    }


}