<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommandeRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommandeController
 * @package App\Controller
 * @Route("/api/commande")
 */
class CommandeController extends AbstractController
{
    private $commandeRepository;

    public function __construct(CommandeRepository $commandeRepository)
    {
        $this->commandeRepository = $commandeRepository;
    }
    private function serializeUser($objet, SerializerInterface $serializer, $groupe="user"): string
    {
        return $serializer->serialize($objet,"json", SerializationContext::create()->setGroups(array($groupe)));
    }

      /**
     * @Route("/create", name="create_commande", methods={"POST"})
     * @param CommandeRepository $commandeRepository
     * @param Request $request
     */
    public function createCommande(CommandeRepository $commandeRepository, Request $request): JsonResponse
    {
 
        $data = json_decode($request->getContent(), true);
        $mail = $data['mail'];
        $nom = $data['nom'];
        $prenom = $data['prenom'];
        $fonction = $data['fonction'];
        $telephone = $data['telephone'];

            if (empty($mail) || empty($nom) || empty($prenom)|| empty($fonction) || empty($telephone)) {
                throw new NotFoundHttpException('Expecting mandatory parameters!');
            } else {
                $this->commandeRepository->saveClient($mail, $nom, $prenom, $fonction, $telephone);
                return new JsonResponse(['status' => 'Commande created!'], Response::HTTP_CREATED);
            } 
    
    }
}
