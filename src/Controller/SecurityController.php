<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;

class SecurityController
{
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }
    /**
    * @Route("/login",name="security_login")
    */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return new Response($this->twig->render(
            'security/login.html.twig',
            [
          'last_username'=> $authenticationUtils->getLastUsername(),
          'error' => $authenticationUtils->getLastAuthenticationError()
        ]
        ));
    }
    /**
    * @Route("/logout",name="security_logout")
    */
    public function logout()// this must be empty since it does nothing..
    {
    }
}
