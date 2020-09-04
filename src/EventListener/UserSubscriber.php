<?php

namespace App\EventListener;

use App\Event\UserRegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \Swift_SmtpTransport;
use \Swift_Mailer;
use \Swift_Message;
use App\Mailer\Mailer;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister',
          ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {
        $user = $event->getRegisteredUser();
        $this->mailer->sendConfirmationEmail($user);
        //dd($message);
    }
}
