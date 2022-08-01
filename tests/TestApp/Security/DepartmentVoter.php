<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Security;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\Department;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DepartmentVoter extends Voter
{
    public function __construct(private readonly AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function supports(string $attribute, $subject): bool
    {
        return is_a($subject, Department::class, true)
            && in_array(CrudOperation::tryFrom($attribute), CrudOperation::all());
    }

    /**
     * {@inheritdoc}
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $crudOperation = CrudOperation::from($attribute);
        return match ($crudOperation) {
            CrudOperation::LIST, CrudOperation::READ => $this->authorizationChecker->isGranted('ROLE_USER'),
            default => false,
        };
    }
}
