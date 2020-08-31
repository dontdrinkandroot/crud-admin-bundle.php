<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultUrlProvider implements UrlProviderInterface
{
    private RoutesResolver $routesResolver;

    private RouterInterface $router;

    private IdResolver $idResolver;

    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(
        RoutesResolver $routesResolver,
        RouterInterface $router,
        IdResolver $idResolver,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->routesResolver = $routesResolver;
        $this->router = $router;
        $this->idResolver = $idResolver;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsUrl(CrudAdminContext $context): bool
    {
        $routes = $this->routesResolver->resolve($context);

        return null !== $routes && array_key_exists($context->getCrudOperation(), $routes);
    }

    /**
     * {@inheritdoc}
     */
    public function provideUrl(CrudAdminContext $context): ?string
    {
        $crudOperation = $context->getCrudOperation();
        if (!$this->authorizationChecker->isGranted(
            $crudOperation,
            $context->getEntity() ?? $context->getEntityClass()
        )) {
            return null;
        }

        $routes = $this->routesResolver->resolve($context);
        switch ($crudOperation) {
            case CrudOperation::LIST:
            case CrudOperation::CREATE:
                return $this->router->generate($routes[$crudOperation]);
            default:
                $id = $this->idResolver->resolve($context);

                return $this->router->generate($routes[$crudOperation], ['id' => $id]);
        }
    }
}
