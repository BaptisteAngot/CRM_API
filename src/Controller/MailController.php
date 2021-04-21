<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Swift_Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProspectController
 * @package App\Controller
 * @Route("/api/mail")
 */

class MailController extends AbstractController
{
    /**
     * @Route("/send", name="mail", methods={"POST"})
     *  @param Request $request
     * @return JsonResponse
     */
    public function sendMsg(Request $request,LoggerInterface $logger, \Swift_Mailer $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mail = $data['mail'];
        $message = $data['message'];

        $swiftmsg = new \Swift_Message('Test email');
        $swiftmsg->setFrom("crmwebpartener@gmail.com");
        $swiftmsg->setTo($mail);
        $swiftmsg->setBody(
            $this->renderView('mail/mail.html.twig',['message'=>$message]))
        ->attach(Swift_Attachment::fromPath('../templates/documents/test.txt'));

       $res = $mailer->send($swiftmsg);
        $logger->info('email sent');
        $this->addFlash('notice', 'Email sent');

        return new JsonResponse(['status' => ' Mail send'.$res], Response::HTTP_CREATED);


    }
    /**
     * @Route("/prospect", name="mail_prospect", methods={"POST"})
     *  @param Request $request
     * @return JsonResponse
     */
    public function sendProspect(Request $request,LoggerInterface $logger, \Swift_Mailer $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mail = $data['mail'];
        $message = $data['message'];

        $swiftmsg = new \Swift_Message('Test email');
        $swiftmsg->setFrom("crmwebpartener@gmail.com");
        $swiftmsg->setTo($mail);
        $swiftmsg->setBody(
            $this->renderView('mail/mailProspect.html.twig',['message'=>$message]),'text/html', 'utf-8');

        $res = $mailer->send($swiftmsg);
        $logger->info('email sent');
        $this->addFlash('notice', 'Email sent');

        return new JsonResponse(['status' => ' Mail send'.$res], Response::HTTP_CREATED);


    }

    /**
     * @Route("/client", name="mail_client", methods={"POST"})
     *  @param Request $request
     * @return JsonResponse
     */
    public function sendClient(Request $request,LoggerInterface $logger, \Swift_Mailer $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mail = $data['mail'];
        $message = isset($data['message']) ? $data['message'] : "";
        $name = isset($data['nom']) ? $data['nom'] : "";


        $swiftmsg = new \Swift_Message('new message !');
            $swiftmsg->setFrom("crmwebpartener@gmail.com");
            $swiftmsg->setTo($mail);
            $swiftmsg->setBody(
                $this->renderView('mail/mailClient.html.twig', ['message' => $message, 'name' => $name]), 'text/html', 'utf-8');

            $res = $mailer->send($swiftmsg);
            $logger->info('email sent');
            $this->addFlash('notice', 'Email sent');

            return new JsonResponse(['status' => ' Mail send' . $res], Response::HTTP_CREATED);
        }

    /**
     * @Route("/client/relance", name="mail_client_relance", methods={"POST"})
     *  @param Request $request
     * @return JsonResponse
     */
    public function sendRelanceClient(Request $request,LoggerInterface $logger, \Swift_Mailer $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mail = $data['mail'];
        $message = $data['message'];
        $name = $data['nom'];

        $swiftmsg = new \Swift_Message('RE:nomrelance');
        $swiftmsg->setFrom("crmwebpartener@gmail.com");
        $swiftmsg->setTo($mail);
        $swiftmsg->setBody(
            $this->renderView('mail/mailRelanceClient.html.twig', ['message' => $message, 'name' => $name]), 'text/html', 'utf-8');

        $res = $mailer->send($swiftmsg);
        $logger->info('email sent');
        $this->addFlash('notice', 'Email sent');

        return new JsonResponse(['status' => ' Mail send' . $res], Response::HTTP_CREATED);
    }
    /**
     * @Route("/client/save", name="mail_client_save", methods={"POST"})
     *  @param Request $request
     * @return JsonResponse
     */
    public function sendSaveClient(Request $request,LoggerInterface $logger, \Swift_Mailer $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mail = $data['mail'];

        $swiftmsg = new \Swift_Message('Compte client enregistrer');
        $swiftmsg->setFrom("crmwebpartener@gmail.com");
        $swiftmsg->setTo($mail);
        $swiftmsg->setBody(
            $this->renderView('mail/mailSaveClient.html.twig'), 'text/html', 'utf-8');

        $res = $mailer->send($swiftmsg);
        $logger->info('email sent');
        $this->addFlash('notice', 'Email sent');

        return new JsonResponse(['status' => ' Mail send' . $res], Response::HTTP_CREATED);
    }

}
