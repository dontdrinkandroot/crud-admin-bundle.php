<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Security;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ExampleEntityVoter extends Voter
{
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject): bool
    {
        return is_a($subject, ExampleEntity::class, true)
            && in_array($attribute, CrudOperation::all());
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return true;
        }

        switch ($attribute) {
            case CrudOperation::LIST:
            case CrudOperation::READ:
                return $this->authorizationChecker->isGranted('ROLE_USER');
        }

        return false;
    }
}
