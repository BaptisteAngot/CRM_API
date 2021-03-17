<?php

namespace App\Controller;

use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializationContext;


/**
 * Class UserController
 * @package App\Controller
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/userProfil", name="user_profil", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getUserProfilFromId(Request $request, UserRepository $userRepository, SerializerInterface $serializer) : JsonResponse
    {
        $response = new JsonResponse();
        $jwtController = new JWTController();
        $headerAuthorization = $request->headers->get("authorization");
        $mail = $jwtController->getUsername($headerAuthorization);
        $user = $userRepository->find($request->request->get("id"));
        if ($user) {
            if ($user->getEmail() == $mail || $jwtController->checkIfAdmin($headerAuthorization) == true ) {
                $jsonContent = $this->serializeUser($user,$serializer);
                $response->setContent($jsonContent);
                $response->setStatusCode(Response::HTTP_OK);
            }else {
                $response->setContent(json_encode("NOT AUTHORIZE TO ACCESS TO THIS DATAS"));
                $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            return $response;
        } else {
            return JsonResponse::fromJsonString(json_encode("This user don't exist"), Response::HTTP_BAD_REQUEST);
        }
    }

    private function serializeUser($objet, SerializerInterface $serializer, $groupe="user") {
        return $serializer->serialize($objet,"json", SerializationContext::create()->setGroups(array($groupe)));
    }
}
