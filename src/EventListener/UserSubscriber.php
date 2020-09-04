<?php

namespace App\EventListener;

use App\Event\UserRegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig\Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig\Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
      UserRegisterEvent::NAME => 'onUserRegister',
    ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
        $body = $this->twig->render('email/registration.html.twig', [
        'user' => $event->getRegisteredUser(),
          ]);
        $message = new \Swift_Message();
        $message->setSubject('Welcome to the micro-post app')
                  ->setFrom('micropost@micropost.com')
                  ->setTo($event->getRegisteredUser()->getEmail())
                  ->setBody('tster here', 'text/html');

        $this->mailer->send($message);
        dd($message);
    }
}
