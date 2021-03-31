<?php

namespace App\Controller;

use App\Repository\OrigineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class OrigineController
 * @package App\Controller
 * @Route("/api/origine")
 */
class OrigineController extends AbstractController
{
    private $origineRepository;

    public function __construct(OrigineRepository $origineRepository)
    {
        $this->origineRepository = $origineRepository;
    }
    /**
     * @Route("/ajout", name="origine_add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */

    public  function addOrigine(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $nom = $data['nom'];
        if (empty($nom)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->origineRepository->saveOrigine($nom);

        return new JsonResponse(['status' => 'Origine created!'], Response::HTTP_CREATED);
    }
    /**
     * @Route("/tous", name="get_all_origine", methods={"GET"})
     */
    public function getAllorigine(): JsonResponse
    {
        $origines = $this->origineRepository->findAll();

        $data = [];
        foreach ($origines as $origine){
            $data[] = [
                'id' => $origine->getId(),
                'nom' => $origine->getNom(),
                'prospects' => $origine->getProspects(),
                'created_at' => $origine->getCreatedAt(),
                'updated_at' => $origine->getUpdatedAt()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
    /**
     * @Route("/{id}", name="get_one_origine", methods={"GET"})
     */
    public function getOrigineID($id): JsonResponse
    {
        $origine = $this->origineRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $origine->getId(),
            'nom' => $origine->getNom(),
            'prospects' => $origine->getProspects(),
            'created_at' => $origine->getCreatedAt(),
            'updated_at' => $origine->getupdatedAt(),

        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
    /**
     * @Route("/edite/{id}", name="update_origine", methods={"PUT"})
     */
    public function pudOrigine($id, Request $request): JsonResponse
    {
        $origine = $this->origineRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);
        empty($data['nom']) ? true : $origine->setNom($data['nom']);


        $updatedOrigine = $this->origineRepository->updateOrigine($origine);

        return new JsonResponse($updatedOrigine->toArray(), Response::HTTP_OK);
    }
    /**
     * @Route("/supr/{id}", name="delete_origine", methods={"DELETE"})
     */
    public function deleteOrigine($id): JsonResponse
    {
        $origine = $this->origineRepository->findOneBy(['id' => $id]);

        $this->origineRepository->removeOrigine($origine);

        return new JsonResponse(['status' => 'Origine deleted'], Response::HTTP_NO_CONTENT);
    }
}
