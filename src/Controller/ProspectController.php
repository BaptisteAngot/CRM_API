<?php

namespace App\Controller;

use App\Repository\ProspectRepository;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class ProspectController
 * @package App\Controller
 * @Route("/api/prospect")
 */
class ProspectController extends AbstractController
{


    private $prospectRepository;

    public function __construct(ProspectRepository $prospectRepository)
    {
        $this->prospectRepository = $prospectRepository;
    }
    /**
     * @Route("/add", name="prospect_add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */

    public  function addProspect(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mail = $data['mail'];
        $nom = $data['nom'];
        $rgpd = $data['rgpd'];
        $origine = $data['origine'];
        $describtion = $data['description'];
        $status = $data['status'];
        if (empty($mail) || empty($rgpd) || empty($origine)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->prospectRepository->saveProspect($mail, $nom,$origine , $rgpd, $describtion,$status);

        return new JsonResponse(['status' => 'Prospect created!'], Response::HTTP_CREATED);
    }
    /**
     * @Route("/all", name="get_all_prospect", methods={"GET"})
     */
    public function getAllProspect(): JsonResponse
    {
        $prospects = $this->prospectRepository->findAll();

        $data = [];
        foreach ($prospects as $prospect){
            $data[] = [
                'id' => $prospect->getId(),
                'mail' => $prospect->getMail(),
                'nom' => $prospect->getNom(),
                'rgpd' => $prospect->getRgpd(),
                'origine' => $prospect->getOrigine()->getNom(),
                'description' => $prospect->getDescription(),
                'created_at' => $prospect->getCreatedAt(),
                'updated_at' => $prospect->getUpdatedAt(),
                'status' => $prospect->getStatus(),
                'disabled' => $prospect->getDisabled()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="get_one_prospect", methods={"GET"})
     */
    public function getProspectID($id): JsonResponse
    {
        $prospect = $this->prospectRepository->findOneBy(['id' => $id]);
        if ($prospect) {

            $data = [
                'id' => $prospect->getId(),
                'mail' => $prospect->getMail(),
                'nom' => $prospect->getNom(),
                'rgpd' => $prospect->getRgpd(),
                'origine' => $prospect->getOrigine(),
                'description' => $prospect->getDescription(),
                'created_at' => $prospect->getCreatedAt(),
                'updated_at' => $prospect->getupdatedAt(),
                'status' => $prospect->getStatus(),
                'disabled' => $prospect->getDisabled()
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        }else{
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }
    }
    /**
     * @Route("/update/{id}", name="update_prospect", methods={"PUT"})
     */
    public function putProspect($id, Request $request): JsonResponse
    {
        $prospect = $this->prospectRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['mail']) ? true : $prospect->setMail($data['mail']);
        empty($data['nom']) ? true : $prospect->setNom($data['nom']);
        empty($data['origine']) ? true : $prospect->setOrigine($data['origine']);
        empty($data['rgpd']) ? true : $prospect->setRgpd($data['rgpd']);
        empty($data['description']) ? true : $prospect->setdescription($data['description']);
        empty($data['status']) ? true : $prospect->setStatus($data['phoneNumber']);
        empty($data['disabled']) ? true : $prospect->setDisabled($data['phoneNumber']);

        $updatedProspect = $this->prospectRepository->updateProspect($prospect);

        return new JsonResponse($updatedProspect->toArray(), Response::HTTP_OK);
    }
    /**
     * @Route("/delete/{id}", name="delete_prospect", methods={"DELETE"})
     */
    public function deleteOrigine($id): JsonResponse
    {
        $prospect = $this->prospectRepository->findOneBy(['id' => $id]);

        $this->prospectRepository->removeProspect($prospect);

        return new JsonResponse(['status' => 'Prospect deleted'], Response::HTTP_NO_CONTENT);
    }
}
