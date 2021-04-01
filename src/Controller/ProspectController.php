<?php

namespace App\Controller;

use App\Entity\Prospect;
use App\Repository\ProspectRepository;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
                'origine' => ['id' => $prospect->getOrigine()->getId(), 'name' => $prospect->getOrigine()->getNom()],
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
        empty($data['status']) ? true : $prospect->setStatus($data['status']);
        empty($data['disabled']) ? true : $prospect->setDisabled($data['disabled']);

        $updatedProspect = $this->prospectRepository->updateProspect($prospect);

        return new JsonResponse($updatedProspect->toArray(), Response::HTTP_OK);
    }
    /**
     * @Route("/delete/{id}", name="delete_prospect", methods={"DELETE"})
     */
    public function deleteProspect($id): JsonResponse
    {
        $prospect = $this->prospectRepository->findOneBy(['id' => $id]);

        $this->prospectRepository->removeProspect($prospect);

        return new JsonResponse(['status' => 'Prospect deleted'], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/disabled/{id}", name="disabled_prospect", methods={"POST"})
     */
    public function disabled_prospect($id) {
        $prospect = $this->prospectRepository->findOneBy(['id' => $id]);
        if ($prospect) {
            $prospect->setDisabled(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new JsonResponse("Prospect at id " . $prospect->getId() . " is now disabled", Response::HTTP_OK);
        }else {
            return new JsonResponse("Prospect don't exist", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/status/{id}", name="status_prospect", methods={"POST"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function status_prospect($id, Request $request) {
        $datas = $request->request->get("status");
        $prospect = $this->prospectRepository->findOneBy(['id' => $id]);
        if ($prospect) {
            if ($datas == Prospect::CHAUD || $datas == Prospect::FROID || $datas == Prospect::TIEDE) {
                $prospect->setStatus($datas);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return new JsonResponse("Prospect at id " . $prospect->getId() . " has now status " . $datas, Response::HTTP_OK);
            }else {
                return new JsonResponse("Bad Request", Response::HTTP_BAD_REQUEST);
            }
        }else {
            return new JsonResponse("Prospect don't exist", Response::HTTP_NOT_FOUND);
        }
    }
}
