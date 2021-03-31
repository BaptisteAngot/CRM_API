<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="api_login", methods={"POST"})
     * @param UserRepository $userRepository
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param JWTTokenManagerInterface $JWTTokenManager
     * @return JsonResponse
     */
    public function api_login(UserRepository $userRepository, Request $request, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $JWTTokenManager) : JsonResponse
    {
        $response = new JsonResponse();
        $datas = json_decode($request->getContent(),true);

        if (isset($datas['email']) && isset($datas['password']) ) {
            $user = $userRepository->findOneBy(['email' => $datas['email']]);
            if ($user) {
                if ($encoder->isPasswordValid($user,$datas['password'])){
                    $responseContent = [
                        'token' => $JWTTokenManager->create($user),
                        'roles' => $user->getRoles(),
                        'username' => $user->getEmail(),
                        'userId' => $user->getId(),
                        'name' => $user->getFullname()
                    ];
                    $response->setContent(json_encode($responseContent));
                    $response->setStatusCode(Response::HTTP_OK);
                }else {
                    $response->setContent("Invalid password or email");
                    $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                }
            }else{
                $response->setContent("User don't exist");
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
        } else {
            $response->setContent("Bad syntax");
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        return $response;
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     * @throws Exception
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new Exception('Don\'t forget to activate logout in security.yaml');
    }
}
