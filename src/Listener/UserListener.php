<?php

namespace App\Listener;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Service\Attribute\Required;

class UserListener
{
    #[Required]
    public UserPasswordEncoderInterface $encoder;

    /**
     * Gets triggered only on insert
     *
     * @param User $user
     * @param LifecycleEventArgs $event
     */
    public function prePersist(User $user, LifecycleEventArgs $event): void
    {
        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
        $user->setCreatedAt(new DateTime("now"));
    }

    /**
     * Gets triggered every time on update
     *
     * @param User $user
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(User $user, PreUpdateEventArgs $event): void
    {
        $user->setUpdatedAt(new DateTime("now"));

        if ($event->hasChangedField("password")) {
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
        }
    }
}
