<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
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

    private function serializeUser($objet, SerializerInterface $serializer, $groupe="user") {
        return $serializer->serialize($objet,"json", SerializationContext::create()->setGroups(array($groupe)));
    }

    /**
     * @Route("/create", name="create_user", methods={"POST"})
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $userRepository
     * @param Request $request
     */
    public function createUser(UserPasswordEncoderInterface $passwordEncoder,UserRepository $userRepository, Request $request): JsonResponse
    {
    
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $roles = $data['roles'];
        $password = $data['password'];
        $lastName = $data['lastName'];
        $firstName = $data['firstName'];
        $telephone = $data['telephone'];
        $fonction = $data["fonction"];

        if (empty($email)||empty($password)|| empty($lastName) ||empty($firstName) || empty($telephone) || empty($fonction)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->userRepository->saveUser($email, $roles, $password, $lastName, $firstName, $telephone, $fonction);

        return new JsonResponse(['status' => 'Customer created!'], Response::HTTP_CREATED);

    }

    //  /**
    //  * @Route("/update/{id}", name="update_user", methods={"PUT"})
    //  */
    // public function updateUser($id, Request $request): JsonResponse
    // {
    //     $user = $this->customerRepository->findOneBy(['id' => $id]);
    //     $data = json_decode($request->getContent(), true);

    //     empty($datas["email"]) ? true : $customer->setFirstName($data['firstName']);
    //     empty($datas["roles"]) ? true : $customer->setLastName($data['lastName']);
    //     empty($datas["password"]) ? true : $customer->setEmail($data['email']);
    //     empty($data['phoneNumber']) ? true : $customer->setPhoneNumber($data['phoneNumber']);
    //     empty($data['phoneNumber']) ? true : $customer->setPhoneNumber($data['phoneNumber']);
    //     empty($data['phoneNumber']) ? true : $customer->setPhoneNumber($data['phoneNumber']);
    //     empty($data['phoneNumber']) ? true : $customer->setPhoneNumber($data['phoneNumber']);

    //     $updatedCostumer = $this->customerRepository->updateCustomer($customer);

    //     return new JsonResponse($updatedCostumer->toArray(), Response::HTTP_OK);
    // }
    // /**
    //  * @Route("/update", name="user_update", methods={"PATCH"})
    //  * @param Request $request
    //  * @param UserRepository $userRepository
    //  * @return Response
    //  */
    // public function updateUsers(Request $request, UserRepository $userRepository)
    // {
    //     $entityManager = $this->getDoctrine()->getManager();
    //     $response = new Response();
    //     $datas = json_decode(
    //         $request->getContent(),
    //         true
    //     );
    
    //     if (isset($datas["user_id"]) && isset($datas["email"])) {
    //         $id = $datas["user_id"];
    //         $user = $userRepository->find($id);
    //         if ($user === null) {
    //             $response->setContent("Ce user n'existe pas");
    //             $response->setStatusCode(Response::HTTP_BAD_REQUEST);
    //         } else {
    //             $user->setEmail($datas["email"])
    //             ->setRoles($datas["roles"])
    //             ->setPassword($datas["password"])
    //             ->setLastName($datas["lastName"])
    //             ->setFirstName($datas["firstName"])
    //             ->setTelephone($datas["telephone"])
    //             ->setFonction($datas["fonction"]);
    //             $entityManager->persist($user);
    //             $entityManager->flush();
    //             $response->setStatusCode(Response::HTTP_OK);
    //             $response->setContent("Modification of user " . $id);
    //         }
    //     } else {
    //         $response->setStatusCode(Response::HTTP_BAD_REQUEST);
    //     }
    //     return $response;
    // }

        /**
     * @Route("/delete", name="delete_user", methods={"DELETE"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function deleteUser(Request $request, UserRepository $userRepository)
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
                $entityManager->remove($user);
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
