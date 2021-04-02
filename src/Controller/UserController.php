<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * Class UserController
 * @package App\Controller
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
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

    private function serializeUser($objet, SerializerInterface $serializer, $groupe="user"): string
    {
        return $serializer->serialize($objet,"json", SerializationContext::create()->setGroups(array($groupe)));
    }

    /**
     * @Route("/create", name="create_user", methods={"POST"})
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $userRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(UserPasswordEncoderInterface $passwordEncoder,UserRepository $userRepository, Request $request): JsonResponse
    {
        $jwtController = new JWTController();
        $headerAuthorization = $request->headers->get("authorization");
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $roles = $data['roles'];
        $password = $data['password'];
        $lastName = $data['lastName'];
        $firstName = $data['firstName'];
        $telephone = $data['telephone'];
        $fonction = $data["fonction"];
        $rgpd = $data["rgpd"];

        if (empty($email) || empty($roles) || empty($password)|| empty($lastName) || empty($firstName) || empty($telephone) || empty($fonction) || $jwtController->checkIfAdmin($headerAuthorization) == false || (empty($rgpd) || $rgpd=false) ) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        } else {
            $this->userRepository->saveUser($email, $roles, $password, $lastName, $firstName, $telephone, $fonction, $rgpd);
            return new JsonResponse(['status' => 'Customer created!'], Response::HTTP_CREATED);
        } 

    }

    /**
     * @Route("/update", name="update_user", methods={"PUT"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function updateUser(Request $request,UserRepository $userRepository,SerializerInterface $serializer): JsonResponse
    {
        $jwtController = new JWTController();
        $headerAuthorization = $request->headers->get("authorization");
        $data = json_decode($request->getContent(),true);
        $mail = $jwtController->getUsername($headerAuthorization);
        $entityManager = $this->getDoctrine()->getManager();
        $user = $userRepository->find($data["id"]);

        if ($user) {
            if ($user->getEmail() == $mail || $jwtController->checkIfAdmin($headerAuthorization) == true ) {
                $user = $userRepository->find($data["id"]);
                isset($data["email"]) && $user->setEmail($data['email']);
                isset($data["roles"]) && $user->setRoles($data['roles']);
                isset($data["password"]) && $user->setPassword($data['password']);
                isset($data["lastName"]) && $user->setLastName($data['lastName']);
                isset($data["firstName"]) && $user->setFirstName($data['firstName']);
                isset($data["telephone"]) && $user->setTelephone($data['telephone']);
                isset($data["fonction"]) && $user->setFonction($data['fonction']);
                $updatedUser = $this->userRepository->updateUser($user);;
                return JsonResponse::fromJsonString($this->serializeUser($user,$serializer));}
                else {
                    return  JsonResponse::fromJsonString("NOT AUTHORIZE TO ACCESS TO THIS DATAS",Response::HTTP_UNAUTHORIZED);
                }
            } else {
                return  JsonResponse::fromJsonString("NOT AUTHORIZE TO ACCESS TO THIS DATAS",Response::HTTP_UNAUTHORIZED);
            }
     
    }

   /**
     * @Route("/getAll", name="get_AllUsers", methods={"GET"})
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return Response
     */
    public function userJson(UserRepository $userRepository, Request $request, SerializerInterface $serializer): Response
    {
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getClassMetadata(User::class)->getFieldNames();
        foreach ($metadata as $value) {
            if ($request->query->get($value)) {
                $filter[$value] = $request->query->get($value);
            }
        }
        return JsonResponse::fromJsonString($this->serializeUser($userRepository->findBy($filter),$serializer));
    }


    /**
     * @Route("/disable", name="disabled_user", methods={"PUT"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function disableUser(Request $request, UserRepository $userRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $response = new Response();
        $datas = json_decode(
            $request->getContent(),
            true
        );
        
        if (isset($datas["id"])) {
            $user = $userRepository->find($datas["id"]);
         
            if ($user === null) {
                $response->setContent("Ce user n'existe pas");
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            } else {
                $user->setDisabled(true);
                $entityManager->persist($user);
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
