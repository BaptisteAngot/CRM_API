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
     * @Route("/ajout", name="prospect_add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */

    public  function addProspect(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mail = $data['mail'];
        $nom = $data['nom'];
        $rgpd = $data['rgpd'];
        $describtion = $data['description'];
        $status = $data['status'];
        if (empty($mail) || empty($rgpd)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->prospectRepository->saveProspect($mail, $nom, $rgpd, $describtion,$status);

        return new JsonResponse(['status' => 'Prospect created!'], Response::HTTP_CREATED);
    }


}
