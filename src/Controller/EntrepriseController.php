<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EntrepriseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

/**
 * Class EntrepriseController
 * @package App\Controller
 * @Route("/api/entreprise")
 */
class EntrepriseController extends AbstractController
{

    private $entrepriseRepository;

    public function __construct(EntrepriseRepository $entrepriseRepository)
    {
        $this->entrepriseRepository = $entrepriseRepository;
    }
    private function serializeUser($objet, SerializerInterface $serializer, $groupe="user"): string
    {
        return $serializer->serialize($objet,"json", SerializationContext::create()->setGroups(array($groupe)));
    }

    /**
     * @Route("/create", name="create_entreprise", methods={"POST"})
     * @param EntrepriseRepository $entrepriseRepository
     * @param Request $request
     */
    public function createEntreprise(EntrepriseRepository $entrepriseRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mail = $data['nom'];
        $nom = $data['mail'];
        $tel = $data['tel'];
        $adresse = $data['adresse'];
        $codePostal = $data['codePostal'];
        $ville = $data['ville'];

            if (empty($mail) || empty($nom) || empty($tel)|| empty($adresse) || empty($codePostal) ||empty($ville)) {
                throw new NotFoundHttpException('Expecting mandatory parameters!');
            } else {
                $this->entrepriseRepository->saveEntreprise($mail, $nom,$tel, $adresse, $codePostal, $ville);
                return new JsonResponse(['status' => 'Entreprise created!'], Response::HTTP_CREATED);
            } 
   
    }
        /**
     * @Route("/update", name="update_entreprise", methods={"PUT"})
     * @param EntrepriseRepository $entrepriseRepository
     * @param SerializerInterface $serializer
     */
    public function updateEntreprise(Request $request,EntrepriseRepository $entrepriseRepository,SerializerInterface $serializer): JsonResponse
    {
    
        $data = json_decode($request->getContent(),true);
                $entityManager = $this->getDoctrine()->getManager();
                $entreprise = $entrepriseRepository->find($data["id"]);
                isset($data["nom"]) && $entreprise->setNom($data['nom']);
                isset($data["mail"]) && $entreprise->setMail($data['mail']);
                isset($data["tel"]) && $entreprise->setTel($data['tel']);
                isset($data["adresse"]) && $entreprise->setAdresse($data['adresse']);
                isset($data["codePostal"]) && $entreprise->setCodePostal($data['codePostal']);
                isset($data["ville"]) && $entreprise->setVille($data['ville']);

                $entreprise->setUpdatedAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
        
                $updatedEntreprise = $this->entrepriseRepository->updateEntreprise($entreprise);
                return JsonResponse::fromJsonString($this->serializeUser($entreprise,$serializer));
    }
    
        /**
     * @Route("/delete", name="delete_entreprise", methods={"DELETE"})
     * @param Request $request
     * @param EntrepriseRepository $entrepriseRepository
     * @return Response
     */
    public function deleteEntreprise(Request $request, EntrepriseRepository $entrepriseRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $response = new Response();
        $data = json_decode(
            $request->getContent(),
            true
        );

        if (isset($data["id"])) {
            $entreprise = $entrepriseRepository->find($data["id"]);
            if ($entreprise === null) {
                $response->setContent("Cet entreprise n'existe pas");
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            } else {
                $entityManager->remove($entreprise);
                $entityManager->flush();
                $response->setContent("Cet entreprise à était supprimé");
                $response->setStatusCode(Response::HTTP_OK);
            }
        } else {
            $response->setContent("L'id n'est pas renseigné");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }
}
