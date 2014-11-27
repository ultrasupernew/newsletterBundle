<?php

namespace Usn\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Usn\NewsletterBundle\Entity\Newsletter;


class DefaultController extends Controller
{
    public function subscribeAction(Request $request)
    {

        $newsletter = new Newsletter();

        $form = $this->createForm('newsletter', $newsletter, array("action" => $this->generateUrl("usn_newsletter_subscribe")));

        if($request->getMethod() == 'POST'){

          $form->handleRequest($request);

          if ($form->isValid()) {

              $em = $this->getDoctrine()->getManager();
              $em->persist($newsletter);
              $em->flush();

              $message = \Swift_Message::newInstance()
                  ->setSubject($this->container->getParameter('confirmation_email_subject'))
                  ->setFrom($this->container->getParameter('newsletter_from_address'))
                  ->setTo($newsletter->getEmail())
                  ->setBody(
                      $this->renderView(
                          'UsnNewsletterBundle:Default:confirmation_mail.txt.twig',
                          array('access_key' => $newsletter->getAccessKey())
                      )
                  );
              $this->get('mailer')->send($message);

              if($request->isXmlHttpRequest()) {

                return $this->createAjaxResponse(array("code" => 200, "message" => "登録が完了しました。"));

              }
              else return $this->redirect($this->generateUrl('usn_newsletter_complete'));
          }
          elseif($request->isXmlHttpRequest()){
            
            $error = $form['email']->getErrors();
            return $this->createAjaxResponse(array("code" => 403, "message" => $error[0]->getMessage() ));

          }

        }

        return $this->render('UsnNewsletterBundle:Default:subscribe.html.twig', array('form' => $form->createView()));
    }

    public function completeAction(){

      return $this->render('UsnNewsletterBundle:Default:complete.html.twig');

    }

    public function unsubscribeAction($access_key) {

      $em = $this->getDoctrine()->getManager();
      
      $newsletter = $em
        ->getRepository('UsnNewsletterBundle:Newsletter')
        ->findOneBy(array('access_key' => $access_key));

      if(!$newsletter) throw $this->createNotFoundException('お探しのページは見つかりませんでした。');

      return $this->render('UsnNewsletterBundle:Default:unsubscribe.html.twig', array('access_key' => $access_key));

    }

    public function unsubscribeCompleteAction($access_key) {

      $em = $this->getDoctrine()->getManager();
      
      $newsletter = $em
        ->getRepository('UsnNewsletterBundle:Newsletter')
        ->findOneBy(array('access_key' => $access_key));

      if(!$newsletter) throw $this->createNotFoundException('お探しのページは見つかりませんでした。');

      $em->remove($newsletter);
      $em->flush();

      return $this->render('UsnNewsletterBundle:Default:unsubscribe_complete.html.twig');

    }

    protected function createAjaxResponse($data) {

      $response = new Response(json_encode($data));
      $response->headers->set('Content-Type', 'application/json');

      return $response;

    }


}
