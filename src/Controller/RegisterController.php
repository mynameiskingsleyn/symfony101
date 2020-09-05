<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Event\UserRegisterEvent;
use App\Security\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RegisterController extends Controller
{
    /**
    * @Route("/register",name="user_register")
    */
    public function register(
        UserPasswordEncoderInterface $passwordEncoder,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        TokenGenerator $tokenGenerator
    ) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setConfirmationToken($tokenGenerator->getRandomSecureToken(30));
            $user->setRoles([User::ROLE_USER]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $userRegisterEvent = new UserRegisterEvent($user);
            $eventDispatcher->dispatch(
                UserRegisterEvent::NAME,
                $userRegisterEvent
            );
            $this->redirectToRoute('micro_post_index');
        }

        return $this->render('register/register.html.twig', [
            'form'=> $form->createView()
        ]);
    }
}
