<?php

namespace App\Controller;

//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
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
            ['count' => $count],
            200
        );
    }

    /**
     * @Route("/all", name="notification_all")
     */
    public function notifications()
    {
        return $this->render('notification/notifications.html.twig', [
        'notifications' => $this->notificationRepo->findBy([
          'user' => $this->getUser(),
          'seen' => false,
        ]),
      ]);
    }

    /**
     * @Route("/acknowledge/{id}", name="notification_acknowledge")
     */
    public function acknowledge(Notification $notification)
    {
        $notification->setSeen(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('notification_all');
    }

    /**
     * @Route("/acknowledge-all", name="notification_acknowledge_all")
     */
    public function acknowledgeAll()
    {
        $this->notificationRepo->markAssAsReadByUser($this->getUser());
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('notification_all');
    }
}
