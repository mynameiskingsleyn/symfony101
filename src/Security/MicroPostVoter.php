<?php
namespace App\Security;

use App\Entity\User;
use App\Entity\MicroPost;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class MicroPostVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE='delete';
    private $logger;
    private $decisionManager;
    public function __construct(LoggerInterface $logger, AccessDecisionManagerInterface $decisionManager)
    {
        //dd('done');
        $this->logger = $logger;
        $this->decisionManager = $decisionManager;
    }
    protected function supports($attribute, $subject)
    {
        //echo "working";
        //dd('working');
        //$this->logger->info('we are at the voter');
        if (!in_array($attribute, [self::EDIT,self::DELETE])) {
            //$this->logger->info('attribute not in');
            return false;
        }
        if (!$subject instanceof MicroPost) {
            //$this->logger->info('not instance of Micropost');
            return false;
        }
        //$this->logger->info('supports passed for support');
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        //dd('working now');
        $authenticatedUser = $token->getUser();
        if (!$authenticatedUser instanceof User) {
            return false;
        }
        if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }

        /** @var MicroPost $microPost  */
        $microPost = $subject;
        if ($microPost->getUser()->getId() === $authenticatedUser->getId()) {
            return true;
        }
        return false;
    }
}
