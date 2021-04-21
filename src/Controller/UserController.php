<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateUserFormType;
use App\Form\UpdateUserFormType;
use App\Repository\ProspectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Class UserController
 * @package App\Controller
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    private $userRepository;
    private $passwordEncoder;
    private $jwtController;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, JWTController $jwtController)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtController = $jwtController;
    }

    /**
     * @Route("/userProfil", name="user_profil", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getUserProfilFromId(Request $request, UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $response = new JsonResponse();
        $jwtController = new JWTController();
        $headerAuthorization = $request->headers->get("authorization");
        $mail = $jwtController->getUsername($headerAuthorization);
        $user = $userRepository->find($request->request->get("id"));
        if ($user) {
            if ($user->getEmail() == $mail || $jwtController->checkIfAdmin($headerAuthorization) == true) {
                $jsonContent = $this->serializeUser($user, $serializer);
                $response->setContent($jsonContent);
                $response->setStatusCode(Response::HTTP_OK);
            } else {
                $response->setContent(json_encode("NOT AUTHORIZE TO ACCESS TO THIS DATAS"));
                $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
            return $response;
        } else {
            return JsonResponse::fromJsonString(json_encode("This user don't exist"), Response::HTTP_BAD_REQUEST);
        }
    }

    private function serializeUser($objet, SerializerInterface $serializer, $groupe = "user"): string
    {
        return $serializer->serialize($objet, "json", SerializationContext::create()->setGroups(array($groupe)));
    }

    /**
     * @Route("/create", name="create_user", methods={"POST"})
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function createUser(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $encoder,UserRepository $userRepository, LoggerInterface $logger, \Swift_Mailer $mailer): JsonResponse
    {
        $headerAuthorization = $request->headers->get("authorization");
        $data = json_decode($request->getContent(), true);
        if ($this->jwtController->checkIfAdmin($headerAuthorization)) {
            $user = new User();
            $form = $this->createForm(CreateUserFormType::class, $user);
            $form->submit($data);

            $violation = $validator->validate($user);
            if (0 !== count($violation)) {
                foreach ($violation as $error) {
                    return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
                }
            }
            $user->setPassword($this->passwordEncoder->encodePassword($user, $data['password']));
            $entityManager->persist($user);
            $entityManager->flush();

                $mail = $data['email'];

                $swiftmsg = new \Swift_Message('Bienvenu');
                $swiftmsg->setFrom("crmwebpartener@gmail.com");
                $swiftmsg->setTo($mail);
                $swiftmsg->setBody(
                    $this->renderView('mail/mailClient.html.twig'), 'text/html', 'utf-8');
                $mailer->send($swiftmsg);
                $logger->info('email sent');
            $swiftmsg2 = new \Swift_Message('Client enregistré');
            $swiftmsg2->setFrom("crmwebpartener@gmail.com");
            $swiftmsg2->setTo("crmwebpartener@gmail.com");
            $swiftmsg2->setBody(
                $this->renderView('mail/mailUserCreate.html.twig'), 'text/html', 'utf-8');
            $mailer->send($swiftmsg2);
            $logger->info('email sent');
            return new JsonResponse("User has been created", Response::HTTP_CREATED);

        } else {
            return new JsonResponse("", Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @Route("/update/{id}", name="update_user", methods={"PUT"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @param int $id
     *
     * @return JsonResponse
     */
    public function updateUser(Request $request, UserRepository $userRepository, ValidatorInterface $validator, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $jsonResponse = new JsonResponse();
        $headerAuthorization = $request->headers->get("authorization");
        $data = json_decode($request->getContent(), true);
        $mail = $this->jwtController->getUsername($headerAuthorization);
        $user = $this->getUser();
        if ($user->getEmail() == $mail || $this->jwtController->checkIfAdmin($headerAuthorization) == true) {
            if ($user) {
                $userFromDb = $userRepository->findOneBy(['id' => $id]);
                $form = $this->createForm(UpdateUserFormType::class, $userFromDb);
                $form->submit($data);

                $violations = $validator->validate($userFromDb);
                if (0 !== count($violations)) {
                    foreach ($violations as $error) {
                        $jsonResponse->setContent($error->getMessage());
                        $jsonResponse->setStatusCode(Response::HTTP_BAD_REQUEST);
                    }
                }

                if (isset($data['password'])) {
                    $userFromDb->setPassword($this->passwordEncoder->encodePassword($userFromDb, $data['password']));
                }
                $entityManager->persist($userFromDb);
                $entityManager->flush();

                $jsonResponse->setContent("User has been updated");
                $jsonResponse->setStatusCode(Response::HTTP_CREATED);
            } else {
                $jsonResponse->setContent("NOT AUTHORIZE TO ACCESS TO THIS DATAS");
                $jsonResponse->setStatusCode(Response::HTTP_UNAUTHORIZED);
            }
        }else {
            $jsonResponse->setContent("NOT AUTHORIZE TO ACCESS TO THIS DATAS");
            $jsonResponse->setStatusCode(Response::HTTP_FORBIDDEN);
        }
        return $jsonResponse;
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
        return JsonResponse::fromJsonString($this->serializeUser($userRepository->findBy($filter), $serializer));
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
