<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Security;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ExampleEntityVoter extends Voter
{
    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    protected function supports($attribute, $subject)
    {
        return is_a($subject, ExampleEntity::class, true)
            && in_array($attribute, CrudOperation::all());
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
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
