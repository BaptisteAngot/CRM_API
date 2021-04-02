<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
/**
 * Class ClientController
 * @package App\Controller
 * @Route("/api/client")
 */
class ClientController extends AbstractController
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
    private function serializeUser($objet, SerializerInterface $serializer, $groupe="user"): string
    {
        return $serializer->serialize($objet,"json", SerializationContext::create()->setGroups(array($groupe)));
    }
    /**
     * @Route("/create", name="create_client", methods={"POST"})
     * @param ClientRepository $clientRepository
     * @param Request $request
     */
    public function createClient(ClientRepository $clientRepository, Request $request): JsonResponse
    {
        $jwtController = new JWTController();
        $headerAuthorization = $request->headers->get("authorization");
        $mail = $jwtController->getUsername($headerAuthorization);
        
        $data = json_decode($request->getContent(), true);
        $mail = $data['mail'];
        $nom = $data['nom'];
        $prenom = $data['prenom'];
        $fonction = $data['fonction'];
        $telephone = $data['telephone'];

            if (empty($mail) || empty($nom) || empty($prenom)|| empty($fonction) || empty($telephone)) {
                throw new NotFoundHttpException('Expecting mandatory parameters!');
            } else {
                $this->clientRepository->saveClient($mail, $nom, $prenom, $fonction, $telephone);
                return new JsonResponse(['status' => 'Customer created!'], Response::HTTP_CREATED);
            } 
    
    }

    /**
     * @Route("/update", name="update_client", methods={"PUT"})
     * @param ClientRepository $clientRepository
     * @param SerializerInterface $serializer
     */
    public function updateClient(Request $request,ClientRepository $clientRepository,SerializerInterface $serializer): JsonResponse
    {
    
        $data = json_decode($request->getContent(),true);
                $entityManager = $this->getDoctrine()->getManager();
                $client = $clientRepository->find($data["id"]);
                isset($data["mail"]) && $client->setMail($data['mail']);
                isset($data["nom"]) && $client->setNom($data['nom']);
                isset($data["prenom"]) && $client->setPrenom($data['prenom']);
                isset($data["fonction"]) && $client->setFonction($data['fonction']);
                isset($data["telephone"]) && $client->setTelephone($data['telephone']);
                $client->setUpdatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        
                $updatedUser = $this->clientRepository->updateClient($client);
                return JsonResponse::fromJsonString($this->serializeUser($client,$serializer));
    }
              
     
    /**
     * @Route("/getAll", name="get_AllClient", methods={"GET"})
     * @param ClientRepository $clientRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return Response
     */
    public function clientJson(ClientRepository $clientRepository, Request $request,SerializerInterface $serializer)
    {
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getClassMetadata(Client::class)->getFieldNames();
        foreach ($metadata as $value) {
            if ($request->query->get($value)) {
                $filter[$value] = $request->query->get($value);
            }
        }
        return JsonResponse::fromJsonString($this->serializeUser($clientRepository->findBy($filter), $serializer));
    }
      /**
     * @Route("/disable", name="disable_client", methods={"PUT"})
     * @param Request $request
     * @param ClientRepository $clientRepository
     * @return Response
     */
    public function disabledClient(Request $request, ClientRepository $clientRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $response = new Response();
        $datas = json_decode(
            $request->getContent(),
            true
        );
        
        if (isset($datas["id"])) {
            $client = $clientRepository->find($datas["id"]);
         
            if ($client === null) {
                $response->setContent("Ce user n'existe pas");
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            } else {
                $client->setDisabled(true);
                $entityManager->persist($client);
                $entityManager->flush();
                $response->setStatusCode(Response::HTTP_OK);
            }
        } else {
            $response->setContent("L'id n'est pas renseigné");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }

}
