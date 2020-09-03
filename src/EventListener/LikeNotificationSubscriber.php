<?php

namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Psr\Log\LoggerInterface;
use App\Entity\MicroPost;
use App\Entity\LikeNotification;

class LikeNotificationSubscriber implements EventSubscriber
{
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function getSubscribedEvents()
    {
        return[
      Events::onFlush
    ];
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate) {
            if (!$collectionUpdate->getOwner() instanceof MicroPost) {
                continue;
            }

            $cMap = $collectionUpdate->getMapping();
            if ($cMap['fieldName'] !== 'likedBy') {
                continue;
            }

            $insertDiff = $collectionUpdate->getInsertDiff(); // array added to Collection
            // this would be an array since we used collection...
            if (!count($insertDiff)) {
                return;
            }
            /** @var MicroPost $microPost */
            $microPost = $collectionUpdate->getOwner();

            $notification = new LikeNotification();
            $notification->setUser($microPost->getUser());
            $notification->setMicroPost($microPost);
            $notification->setLikedBy(reset($insertDiff));

            $em->persist($notification);
            $uow->computeChangeSet(
                $em->getClassMetadata(LikeNotification::class),
                $notification
            );
            //$em->flush();
        }
    }
}
