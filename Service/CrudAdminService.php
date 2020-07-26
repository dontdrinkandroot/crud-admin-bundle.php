<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\CrudAdminBundle\Service\CollectionProvider\CollectionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinitionProvider\FieldDefinitionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\FormProvider\FormProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ItemProvider\ItemProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteProvider\RouteProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\TitleProvider\TitleProviderInterface;
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

    /** @var TitleProviderInterface[] */
    private array $titleProviders = [];

    /** @var ItemProviderInterface[] */
    private array $itemProviders = [];

    /** @var CollectionProviderInterface[] */
    private array $collectionProviders = [];

    /** @var FieldDefinitionProviderInterface[] */
    private array $fieldDefinitionProviders = [];

    /** @var RouteProviderInterface[] */
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

    /**
     * @param CrudAdminRequest $crudAdminRequest
     *
     * @return PaginationInterface|array
     */
    public function listEntities(CrudAdminRequest $crudAdminRequest)
    {
        $data = $crudAdminRequest->getData();
        if (null !== $data) {
            return $data;
        }

        foreach ($this->collectionProviders as $collectionProvider) {
            if ($collectionProvider->supports($crudAdminRequest)) {
                $data = $collectionProvider->provideCollection($crudAdminRequest);
                if (null !== $data) {
                    $crudAdminRequest->setData($data);

                    return $data;
                }
            }
        }

        throw new RuntimeException('Could not list entities');
    }

    public function getTemplate(CrudAdminRequest $crudAdminRequest): string
    {
        $template = $crudAdminRequest->getTemplate();
        if (null !== $template) {
            return $template;
        }

        switch ($crudAdminRequest->getOperation()) {
            case CrudOperation::LIST:
                return '@DdrCrudAdmin/list.html.twig';
            case CrudOperation::READ:
                return '@DdrCrudAdmin/read.html.twig';
            case CrudOperation::UPDATE:
            case CrudOperation::CREATE:
                return '@DdrCrudAdmin/update.html.twig';
        }

        throw new RuntimeException('Could not resolve template');
    }

    public function render(string $template, array $context = []): Response
    {
        return new Response($this->templating->render($template, $context));
    }

    public function getEntity(CrudAdminRequest $crudAdminRequest): ?object
    {
        $entity = $crudAdminRequest->getData();
        if (null !== $entity) {
            return $entity;
        }

        foreach ($this->itemProviders as $itemProvider) {
            if ($itemProvider->supports($crudAdminRequest)) {
                $entity = $itemProvider->provideItem($crudAdminRequest);
                if (null !== $entity) {
                    $crudAdminRequest->setData($entity);

                    return $entity;
                }
            }
        }

        throw new RuntimeException('Could not resolve entity');
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

    private function getId(CrudAdminRequest $crudAdminRequest)
    {
        $id = $crudAdminRequest->getId();
        if (null === $id) {
            throw new RuntimeException('No Id found');
        }

        return $id;
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

    public function getTitle(CrudAdminRequest $crudAdminRequest): string
    {
        $title = $crudAdminRequest->getTitle();
        if (null !== $title) {
            return $title;
        }

        foreach ($this->titleProviders as $titleProvider) {
            if ($titleProvider->supports($crudAdminRequest)) {
                $title = $titleProvider->provideTitle($crudAdminRequest);
                if (null !== $title) {
                    $crudAdminRequest->setTitle($title);

                    return $title;
                }
            }
        }

        throw new RuntimeException('Could not resolve title');
    }

    public function getForm(CrudAdminRequest $crudAdminRequest): FormInterface
    {
        $form = $crudAdminRequest->getForm();
        if (null !== $form) {
            return $form;
        }

        foreach ($this->formProviders as $formProvider) {
            if ($formProvider->supports($crudAdminRequest)) {
                $form = $formProvider->provideForm($crudAdminRequest);
                if (null !== $form) {
                    $crudAdminRequest->setForm($form);

                    return $form;
                }
            }
        }

        throw new RuntimeException('Could not resolve form');
    }

    public function getRoutes(CrudAdminRequest $crudAdminRequest)
    {
        $routes = $crudAdminRequest->getRoutes();
        if (null !== $routes) {
            return $routes;
        }

        foreach ($this->routeProviders as $routeProvider) {
            if ($routeProvider->supports($crudAdminRequest)) {
                $routes = $routeProvider->provideRoutes($crudAdminRequest);
                if (null !== $routes) {
                    $crudAdminRequest->setRoutes($routes);

                    return $routes;
                }
            }
        }

        throw new RuntimeException('Could not resolve routes');
    }

    /**
     * @param CrudAdminRequest $crudAdminRequest
     *
     * @return FieldDefinition[]
     */
    public function getFieldDefinitions(CrudAdminRequest $crudAdminRequest): array
    {
        $fieldDefinitions = $crudAdminRequest->getFieldDefinitions();
        if (null !== $fieldDefinitions) {
            return $fieldDefinitions;
        }

        foreach ($this->fieldDefinitionProviders as $fieldDefinitionProvider) {
            if ($fieldDefinitionProvider->supports($crudAdminRequest)) {
                $fieldDefinitions = $fieldDefinitionProvider->provideFieldDefinitions($crudAdminRequest);
                if (null !== $fieldDefinitions) {
                    $crudAdminRequest->setFieldDefinitions($fieldDefinitions);

                    return $fieldDefinitions;
                }
            }
        }

        throw new RuntimeException('Could not resolve field definitions');
    }

    public function addTitleProvider(TitleProviderInterface $titleProvider)
    {
        $this->titleProviders[] = $titleProvider;
    }

    public function addItemProvider(ItemProviderInterface $itemProvider)
    {
        $this->itemProviders[] = $itemProvider;
    }

    public function addCollectionProvider(CollectionProviderInterface $collectionProvider)
    {
        $this->collectionProviders[] = $collectionProvider;
    }

    public function addFieldDefinitionProvider(FieldDefinitionProviderInterface $fieldDefinitionProvider)
    {
        $this->fieldDefinitionProviders[] = $fieldDefinitionProvider;
    }

    public function addRouteProvider(RouteProviderInterface $routeProvider)
    {
        $this->routeProviders[] = $routeProvider;
    }

    public function addFormProvider(FormProviderInterface $formProvider)
    {
        $this->formProviders[] = $formProvider;
    }

}
