<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Entity\Client;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\ProspectRepository;
use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerInterface;
/**
 * Class ClientController
 * @package App\Controller
 * @Route("/api/rendezvous")
 */
class RendezVousController extends AbstractController
{
  /**
     * @Route("/create", name="create_rendez_vous", methods={"POST"})
     * @param Request $request
     * @param ClientRepository $clientRepository
     * @param ProspectRepository $prospectRepository
     * @param UserRepository $userRepository
     * @param RendezVousRepository $rendezVousRepository
     * @return Response
     * @throws \Exception
     */
    public function createRendezVous(Request $request,ClientRepository $clientRepository, ProspectRepository $prospectRepository ,UserRepository $userRepository, RendezVousRepository $rendezVousRepository): Response
    {
            $entityManager = $this->getDoctrine()->getManager();

            $newRendezVous = new RendezVous();
            $response = new Response();

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
                $response->setContent('Saved new commune with id ' . $newRendezVous->getId() );
                }

            }
            return $response;

    }


    /**
     * @Route("/getrendezvous", name="GET_rendez_vous", methods={"GET"})
     * @param Request $request
     * @param RendezVousRepository $rendezVousRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getRendezVousByUser(Request $request,RendezVousRepository $rendezVousRepository,SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $rendezVous = $rendezVousRepository->findBy(['userIdHost' => $user->getId()]);

        return JsonResponse::fromJsonString($serializer->serialize($rendezVous, 'json'), Response::HTTP_OK);
     
    }

    /**
     * @Route("/update", name="update_rendezvous", methods={"PUT"})
     * @param RendezVousRepository $rendezVousRepository
     * @param ClientRepository $clientRepository
     * @param ProspectRepository $prospectRepository
     * @param SerializerInterface $serializer
     */
    public function updaterendezvous(Request $request,RendezVousRepository $rendezVousRepository,ClientRepository $clientRepository, ProspectRepository $prospectRepository , SerializerInterface $serializer): JsonResponse
    {
    
        $data = json_decode($request->getContent(),true);
                $entityManager = $this->getDoctrine()->getManager();
                $rendezVous = $rendezVousRepository->find($data["id"]);
             
                isset($data["dateStart"]) && $rendezVous->setDateStart(new \DateTime($data['dateStart']));
                isset($data["dateEnd"]) && $rendezVous->setDateEnd(new \DateTime($data['dateEnd']));
                isset($data["description"]) && $rendezVous->setDescription($data['description']);
                isset($data["userIdHost"]) && $rendezVous->setUserIdHost($data['userIdHost']); 
                isset($data["invitedMail"]) && $rendezVous->setInvitedMail($data['invitedMail']);

                if(isset($data["clientId"])) {
                    $client = $clientRepository->find($data['clientId']);
                    if($client) {
                       $rendezVous->setClientId($client);
                    }
                }
                if(isset($data["prospectId"])) {
                    $prospectId = $prospectRepository->find($data['prospectId']);
                    if($prospectId) {
                       $rendezVous->setProspectId($prospectId);
                    }
                }

                $entityManager->persist($rendezVous);
                $entityManager->flush();
                return JsonResponse::fromJsonString($serializer->serialize($rendezVous, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/delete", name="delete_rendezvous", methods={"DELETE"})
     * @param Request $request
     * @param RendezVousRepository $rendezVousRepository
     * @return Response
     */
    public function GameDelete(Request $request, RendezVousRepository $rendezVousRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $response = new Response();
        $data = json_decode(
            $request->getContent(),
            true
        );
        if (isset($data["id"])) {
            $rendezvous = $rendezVousRepository->find($data["id"]);
            if ($rendezvous === null) {
                $response->setContent("Ce rendezvous n'existe pas" + $game);
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            } else {
                $entityManager->remove($rendezvous);
                $entityManager->flush();
                $response->setContent("Ce rendezvous à était supprimé");
                $response->setStatusCode(Response::HTTP_OK);
            }
        } else {
            $response->setContent("L'id n'est pas renseigné");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }

}