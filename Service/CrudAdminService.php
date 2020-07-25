<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Crud\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\CollectionProvider\CollectionProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\ItemProvider\ItemProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\TitleProvider\TitleProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\DefaultUuidEntity;
use Dontdrinkandroot\CrudAdminBundle\Request\CrudAdminRequest;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Templating\EngineInterface;
use Twig\Environment;

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

    public function checkAuthorization(CrudAdminRequest $crudAdminRequest)
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
        $entityClass = $this->getEntityClass($crudAdminRequest);
        $entityManager = $this->getEntityManager($crudAdminRequest);
        $id = $this->getId($crudAdminRequest);
        if (Uuid::isValid($id) && is_a($entityClass, DefaultUuidEntity::class, true)) {
            $persister = $entityManager->getUnitOfWork()->getEntityPersister($entityClass);

            return $persister->load(['uuid' => $id]);
        }

        return $entityManager->find($entityClass, $id);
    }

    private function getEntityClass(CrudAdminRequest $crudAdminRequest): string
    {
        $entityClass = $crudAdminRequest->getEntityClass();
        if (null === $entityClass) {
            throw new RuntimeException('Entity class not found');
        }

        return $entityClass;
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
}
