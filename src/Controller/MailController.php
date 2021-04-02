<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $this->renderView('mail/mail.html.twig',['message'=>$message]));

       $res = $mailer->send($swiftmsg);
        $logger->info('email sent');
        $this->addFlash('notice', 'Email sent');

        return new JsonResponse(['status' => ''.$res], Response::HTTP_CREATED);


    }
}
