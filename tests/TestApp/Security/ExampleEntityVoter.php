<?php

namespace Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Security;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Tests\TestApp\Entity\ExampleEntity;
use Override;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string,string|ExampleEntity>
 */
class ExampleEntityVoter extends Voter
{
    public function __construct(private readonly AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    #[Override]
    protected function supports(string $attribute, $subject): bool
    {
        return is_a($subject, ExampleEntity::class, true)
            && in_array(CrudOperation::tryFrom($attribute), CrudOperation::all());
    }

    #[Override]
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
