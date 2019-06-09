<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

/**
* @Security("is_granted('ROLE_USER')")
* @ROUTE("/following")
*/
class FollowingController extends Controller
{
    /**
    * @Route("/follow/{id}", name="following_follow")
    */
    public function follow(User $userToFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        if ($currentUser->getId() != $userToFollow->getId()) {
            //$currentUser->getFollowing()->add($userToFollow);
            $currentUser->follow($userToFollow);
            $entityManager->flush();
        }

        return $this->redirectToRoute('micro_post_user', ['username'=>$userToFollow->getUsername()]);
    }
    /**
    * @Route("/unfollow/{id}", name="following_unfollow")
    */
    public function unfollow(User $userToUnFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $currentUser->getFollowing()
                      ->removeElement($userToUnFollow);
        $entityManager->flush();

        return $this->redirectToRoute('micro_post_user', ['username'=>$userToUnFollow->getUsername()]);
    }
}
