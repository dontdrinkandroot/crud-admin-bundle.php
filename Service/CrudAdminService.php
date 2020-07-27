<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\Collection\CollectionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitions\FieldDefinitionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Form\FormProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\DefaultUuidEntity;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Environment;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class CrudAdminService
{
    private EventDispatcherInterface $eventDispatcher;

    private ManagerRegistry $managerRegistry;

    private Environment $templating;

    private AuthorizationCheckerInterface $authorizationChecker;

    private RouterInterface $router;

    /** @var ItemProviderInterface[] */
    private array $itemProviders = [];

    /** @var \Dontdrinkandroot\CrudAdminBundle\Service\Routes\RoutesProviderInterface[] */
    private array $routeProviders = [];

    /** @var FormProviderInterface[] */
    private array $formProviders = [];

    public function __construct(
        ManagerRegistry $managerRegistry,
        Environment $templating,
        EventDispatcherInterface $eventDispatcher,
        AuthorizationCheckerInterface $authorizationChecker,
        RouterInterface $router
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->managerRegistry = $managerRegistry;
        $this->templating = $templating;
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
    }

    public function checkAuthorization(CrudAdminRequest $crudAdminRequest): bool
    {
        $data = $crudAdminRequest->getData();
        $authSubject = $data ?? $this->getEntityClass($crudAdminRequest);

        $crudOperation = $crudAdminRequest->getOperation();

        return $this->authorizationChecker->isGranted($crudOperation, $authSubject);
    }

    public function render(string $template, array $context = []): string
    {
        return $this->templating->render($template, $context);
    }

    private function getEntityClass(CrudAdminRequest $crudAdminRequest): string
    {
        $entityClass = $crudAdminRequest->getEntityClass();
        if (null === $entityClass) {
            throw new RuntimeException('Entity class not found');
        }

        return $entityClass;
    }

    public function createNewInstance(CrudAdminRequest $crudAdminRequest)
    {
        $entityClass = $crudAdminRequest->getEntityClass();

        return new $entityClass();
    }

    public function createResponse(CrudAdminRequest $crudAdminRequest): Response
    {
        switch ($crudAdminRequest->getOperation()) {
            case CrudOperation::DELETE:
                return $this->createDeleteReponse($crudAdminRequest);
        }

        throw new RuntimeException('Dont know how to create response for ' . $crudAdminRequest->getOperation());
    }

    private function createDeleteReponse(CrudAdminRequest $crudAdminRequest)
    {
        $entityManager = $this->getEntityManager($crudAdminRequest);
        $entity = $this->getEntity($crudAdminRequest);
        $entityManager->remove($entity);
        $entityManager->flush();

        $redirectRoute = $crudAdminRequest->getRedirectRouteAfterSuccess();
        if (null !== $redirectRoute) {
            return new RedirectResponse($this->router->generate($redirectRoute));
        }

        return new Response('OK');
    }

    public function getEntityManager(CrudAdminRequest $crudAdminRequest): EntityManagerInterface
    {
        $entityManager = $this->managerRegistry->getManagerForClass($crudAdminRequest->getEntityClass());
        if (null === $entityManager) {
            throw new RuntimeException('Entity Manager not found');
        }
        assert($entityManager instanceof EntityManagerInterface);

        return $entityManager;
    }

    public function getForm(CrudAdminRequest $crudAdminRequest): FormInterface
    {
        $form = $crudAdminRequest->getForm();
        if (null !== $form) {
            return $form;
        }

        foreach ($this->formProviders as $formProvider) {
            if ($formProvider->supports($crudAdminRequest->getRequest())) {
                $form = $formProvider->provideForm($crudAdminRequest->getRequest());
                if (null !== $form) {
                    $crudAdminRequest->setForm($form);

                    return $form;
                }
            }
        }

        throw new RuntimeException('Could not resolve form');
    }

      public function addItemProvider(ItemProviderInterface $itemProvider)
    {
        $this->itemProviders[] = $itemProvider;
    }

      public function addRouteProvider(RoutesProviderInterface $routeProvider)
    {
        $this->routeProviders[] = $routeProvider;
    }

    public function addFormProvider(FormProviderInterface $formProvider)
    {
        $this->formProviders[] = $formProvider;
    }
}
