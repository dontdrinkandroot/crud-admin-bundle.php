<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service\Url;

use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdResolver;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesResolver;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @var AuthorizationCheckerInterface
     */
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
    public function supports(string $entityClass, string $crudOperation, Request $request): bool
    {
        $routes = $this->routesResolver->resolve($request);

        return null !== $routes && array_key_exists($crudOperation, $routes);
    }

    /**
     * {@inheritdoc}
     */
    public function provideUrl($entityOrClass, string $crudOperation, Request $request): ?string
    {
        if (!$this->authorizationChecker->isGranted($crudOperation, $entityOrClass)) {
            return null;
        }
        $routes = $this->routesResolver->resolve($request);
        switch ($crudOperation) {
            case CrudOperation::LIST:
                return $this->router->generate($routes[$crudOperation]);
            default:
                $id = $this->idResolver->resolve($entityOrClass);

                return $this->router->generate($routes[$crudOperation], ['id' => $id]);
        }
    }
}
