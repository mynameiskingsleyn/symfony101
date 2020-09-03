<?php

namespace App\Controller;

//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\NotificationRepository;

/*
* @Security("is_granted('IS_AUTHENTICATED_FULLY')")
* @Route("/notification")
*/
class NotificationController extends Controller
{
    /**
    * @var NotificationRepository
    */
    private $notificationRepo;

    public function __construct(NotificationRepository $notificationRepo)
    {
        $this->notificationRepo = $notificationRepo;
    }
    /**
     * @Route("/unread-count", name="notification_unread")
     */
    public function unreadCount()
    {
        $currentUser = $this->getUser();
        $count = $this->notificationRepo->findUnseenByUser($currentUser);

        return new JsonResponse(
            ['count'=>$count],
            200
        );
    }
}
