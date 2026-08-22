CRUD Admin Bundle
=================

Continuous Integration
---------------------

[![Latest Stable Version](http://poser.pugx.org/dontdrinkandroot/crud-admin-bundle/v)](https://packagist.org/packages/dontdrinkandroot/crud-admin-bundle)
[![License](http://poser.pugx.org/dontdrinkandroot/crud-admin-bundle/license)](https://packagist.org/packages/dontdrinkandroot/crud-admin-bundle)
![Continuous Integration](https://github.com/dontdrinkandroot/crud-admin-bundle.php/actions/workflows/continuous-integration.yml/badge.svg)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=dontdrinkandroot_crud-admin-bundle.php&metric=coverage)](https://sonarcloud.io/dashboard?id=dontdrinkandroot_crud-admin-bundle.php)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=dontdrinkandroot_crud-admin-bundle.php&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=dontdrinkandroot_crud-admin-bundle.php)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=dontdrinkandroot_crud-admin-bundle.php&metric=reliability_rating)](https://sonarcloud.io/dashboard?id=dontdrinkandroot_crud-admin-bundle.php)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=dontdrinkandroot_crud-admin-bundle.php&metric=security_rating)](https://sonarcloud.io/dashboard?id=dontdrinkandroot_crud-admin-bundle.php)

Overview
--------

This bundle provides a generic CRUD admin interface for Symfony applications with Doctrine support. For each registered entity, the standard CRUD operations (list, create, read, update, delete) are exposed through automatically generated routes, forms, templates and URLs. Behavior can be customized declaratively via YAML configuration or programmatically via provider services.

Installation
------------

```bash
composer require dontdrinkandroot/crud-admin-bundle
```

Requirements:

- PHP >= 8.5
- Symfony >= 7.4
- [KNP Paginator Bundle](https://github.com/KnpLabs/KnpPaginatorBundle) (`knplabs/knp-paginator-bundle`)

When using [Symfony Flex](https://flex.symfony.com/), the recipe automatically imports the routes of the bundle. If you do not use Flex, import them manually in your routing configuration:

```php
// config/routes/ddr_crud_admin.php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->import('.', 'ddr_crud');
};
```

Configuration
-------------

### Bundle configuration

The bundle accepts the following options under the `ddr_crud_admin` key:

```yaml
# config/packages/ddr_crud_admin.yaml
ddr_crud_admin:
    humanize: true                 # Humanize field labels and titles (default: true)
    title_type: auto               # 'auto' or 'manual' (default: 'auto')
```

### Entity configuration

Place YAML files in `config/ddr_crud_admin/` (or `config/ddr_crud/`) of your project or in the `Resources/config/ddr_crud_admin` (or `Resources/config/ddr_crud`) directory of a bundle. Each file is keyed by the fully qualified class name of the entity:

```yaml
# config/ddr_crud_admin/App/Entity/Department.yaml
App\Entity\Department:
    form_type: App\Form\Type\DepartmentType
    translation_domain: App
    route:
        name_prefix: ddr_crud.department
        path_prefix: /departments
    default_sort:
        field: name
        order: ASC
    templates:
        list: department/list.html.twig
        create: department/form.html.twig
        read: department/read.html.twig
        update: department/form.html.twig
        delete: department/delete.html.twig
    field_definitions:
        - property_path: name
          display_type: string
          crud_operations: [list, read, create, update]
          sortable: true
          filterable: true
        - property_path: description
          display_type: text
          crud_operations: [read, update]
```

Available options per entity:

| Option                | Description                                                            |
|-----------------------|------------------------------------------------------------------------|
| `form_type`           | Form type class used for create and update operations.                 |
| `translation_domain`  | Translation domain used for labels and titles.                         |
| `route.name_prefix`   | Prefix of the generated route names.                                   |
| `route.path_prefix`   | Prefix of the generated route paths.                                   |
| `default_sort`        | Default sort order, consisting of `field` and `order`.                 |
| `templates`           | Template per CRUD operation (`list`, `create`, `read`, `update`, `delete`). |
| `field_definitions`   | List of field definitions (see below).                                 |

Available options per field definition:

| Option             | Description                                                                 |
|--------------------|-----------------------------------------------------------------------------|
| `property_path`    | Property path of the field (required).                                      |
| `display_type`     | Display type of the field, e.g. `string`, `text`, `date`, `datetime` (required). |
| `crud_operations`  | CRUD operations this field applies to: `list`, `create`, `read`, `update`, `delete` (required). |
| `form_type`        | Form type class used for this field.                                       |
| `sortable`         | Whether the field can be used for sorting (default: `false`).               |
| `filterable`       | Whether the field can be filtered (default: `false`).                       |
| `filter_path`      | Property path used for filtering when it differs from `property_path`.       |

The configuration is processed by `CrudConfigCompilerPass`, which registers static providers at a high priority. Entity configuration takes precedence over the default providers. Services implementing provider interfaces are registered with priority `0` by default, which is higher than `PRIORITY_HIGH`, so they take precedence over the YAML configuration (see [Customizing Providers](#customizing-providers)).

### Routes

Routes for all CRUD operations are generated automatically by `CrudRoutesLoader` for every registered controller. The default route names and paths are derived from the entity class name. They can be inspected with the built-in command:

```bash
bin/console ddr:crud-admin:info
```

### Security

Authorization is enforced by `AuthorizationListener` on the `PreSetDataEvent`/`PostSetDataEvent`. For `list` and `create`, the attribute is checked against the entity class; for `read`, `update` and `delete`, it is checked against the entity instance:

```php
// list, create
$authorizationChecker->isGranted(CrudOperation::LIST->value, $entityClass);
// read, update, delete
$authorizationChecker->isGranted(CrudOperation::READ->value, $entity);
```

Implement a security voter to grant access per operation:

```php
namespace App\Security;

use App\Entity\Department;
use Dontdrinkandroot\Common\CrudOperation;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string,string|Department>
 */
class DepartmentVoter extends Voter
{
    public function __construct(private readonly AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return is_a($subject, Department::class, true)
            && in_array(CrudOperation::tryFrom($attribute), CrudOperation::all(), true);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token, ?Vote $vote = null): bool
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
```

Note that the `delete` action checks the attribute against the entity, and that `RedirectAfterWriteListener` only redirects to the list page if the user is still allowed to view it.

Creating CRUD Controllers
-------------------------

### Simple controllers

The simplest way to expose an entity is to extend `CrudController` and pass the entity class to the parent constructor:

```php
namespace App\Controller;

use App\Entity\Department;
use Dontdrinkandroot\CrudAdminBundle\Controller\CrudController;

/**
 * @extends CrudController<Department>
 */
class DepartmentController extends CrudController
{
    public function __construct()
    {
        parent::__construct(Department::class);
    }
}
```

Controllers implementing `CrudControllerInterface` are automatically registered as services (tagged `ddr_crud_admin.controller`), so no additional service configuration is required.

### Configurable controllers

For per-controller customization without YAML configuration, extend `ConfigurableCrudController` and override one or more of the following protected methods:

- `getEntityClass()`: The entity class (required, not defined by the abstract base).
- `getFormType()`: Form type class for create and update operations.
- `getTemplate(CrudOperation $crudOperation)`: Template per CRUD operation.
- `getDefaultSort()`: Default sort order.
- `getFieldDefinitions()`: List of field definitions.
- `getNamePrefix()`: Prefix of the generated route names.
- `getPathPrefix()`: Prefix of the generated route paths.

Note that `ConfigurableCrudController` does not extend `CrudController`, so it does not receive the entity class via a constructor. You have to implement `getEntityClass()` yourself:

```php
namespace App\Controller;

use App\Entity\Department;
use Dontdrinkandroot\CrudAdminBundle\Controller\ConfigurableCrudController;
use Dontdrinkandroot\CrudAdminBundle\Model\DefaultSort;

/**
 * @extends ConfigurableCrudController<Department>
 */
class DepartmentController extends ConfigurableCrudController
{
    public function getEntityClass(): string
    {
        return Department::class;
    }

    protected function getFormType(): ?string
    {
        return DepartmentType::class;
    }

    protected function getDefaultSort(): ?DefaultSort
    {
        return new DefaultSort('name', 'ASC');
    }
}
```

Customizing Providers
---------------------

The behavior of the bundle is determined by providers, which are resolved through resolver services. Each resolver iterates over all providers registered for a given tag and returns the first non-null result, ordered by priority.

### Provider interfaces

Providers are grouped by concern, each with a dedicated interface and tag:

| Concern                | Interface                                         | Tag                                      |
|------------------------|---------------------------------------------------|------------------------------------------|
| Titles                 | `TitleProviderInterface`                          | `ddr_crud_admin.title_provider`          |
| Templates              | `TemplateProviderInterface`                       | `ddr_crud_admin.template_provider`       |
| Routes                 | `RouteInfoProviderInterface`                      | `ddr_crud_admin.route_info_provider`     |
| URLs                   | `UrlProviderInterface`                            | `ddr_crud_admin.url_provider`            |
| Form types             | `FormTypeProviderInterface`                       | `ddr_crud_admin.form_type_provider`      |
| Forms                  | `FormProviderInterface`                           | `ddr_crud_admin.form_provider`           |
| Field definitions      | `FieldDefinitionsProviderInterface`               | `ddr_crud_admin.field_definitions_provider` |
| Field renderers        | `FieldRendererProviderInterface`                  | `ddr_crud_admin.field_renderer_provider` |
| Default sort           | `DefaultSortProviderInterface`                    | `ddr_crud_admin.default_sort_provider`   |
| Items                  | `ItemProviderInterface`                           | `ddr_crud_admin.item_provider`           |
| Item persisters        | `ItemPersisterProviderInterface`                  | `ddr_crud_admin.item_persister_provider` |
| IDs                    | `IdProviderInterface`                             | `ddr_crud_admin.id_provider`             |
| Pagination             | `PaginationProviderInterface`                     | `ddr_crud_admin.pagination_provider`     |
| Pagination targets     | `PaginationTargetProviderInterface`               | `ddr_crud_admin.pagination_target_provider` |
| Translation domains    | `TranslationDomainProviderInterface`              | `ddr_crud_admin.translation_domain_provider` |
| Query extensions       | `QueryExtensionProviderInterface`                 | `ddr_crud_admin.query_extension_provider` |
| Query builder extensions | `QueryBuilderExtensionProviderInterface`        | `ddr_crud_admin.query_builder_extension_provider` |

### Registering a custom provider

Services implementing one of these interfaces are automatically tagged via autoconfiguration, so no explicit tag is required. A provider returns `null` when it cannot handle the given entity, in which case the resolver falls back to the next provider.

The following example provides a custom title for entities of a specific class:

```php
namespace App\Provider;

use App\Entity\Department;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Title\TitleProviderInterface;

/**
 * @template T of object
 * @implements TitleProviderInterface<T>
 */
class DepartmentTitleProvider implements TitleProviderInterface
{
    public function provideTitle(string $entityClass, CrudOperation $crudOperation, ?object $entity): ?string
    {
        if ($entityClass !== Department::class) {
            return null;
        }

        return 'Departments';
    }
}
```

```yaml
# config/services.yaml
services:
    App\Provider\DepartmentTitleProvider: ~
```

### Priority

Providers are ordered by priority. The bundle defines the following constants in `DdrCrudAdminExtension`:

- `PRIORITY_LOW` (-256): default behavior provided by the bundle.
- `PRIORITY_MEDIUM` (-192): intermediate behavior.
- `PRIORITY_HIGH` (-128): entity-specific behavior, e.g. generated from YAML configuration.

A custom provider can be tagged with an explicit priority to override other providers:

```yaml
# config/services.yaml
services:
    App\Provider\DepartmentTitleProvider:
        tags:
            - { name: ddr_crud_admin.title_provider, priority: 0 }
```

Events
------

The controller actions dispatch events that allow you to customize the request handling. All events carry the entity class, the CRUD operation and the request.

| Event                     | Dispatched                                             |
|---------------------------|--------------------------------------------------------|
| `PreSetDataEvent`         | Before the data of an operation is resolved. Listeners may throw `AbortWithResponseException` to return a custom response, or `AccessDeniedException` to deny access. |
| `PostSetDataEvent`        | After the data (pagination, entity or null) has been resolved. Same abort/deny capabilities as `PreSetDataEvent`. |
| `PrePersistEvent`         | Before an entity is persisted (create, update, delete). |
| `PostPersistEvent`        | After an entity has been persisted. The default listener adds a success flash message. |
| `RedirectAfterWriteEvent` | After a successful write, before redirecting. Set the `response` property to override the redirect target. |
| `ViewModelEvent`          | Before rendering a view. The `context` property holds the template variables and can be modified. |

Example listener that sets a custom redirect target after a write:

```php
namespace App\Event\Listener;

use App\Entity\Department;
use Dontdrinkandroot\CrudAdminBundle\Event\RedirectAfterWriteEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectToDepartmentListener
{
    public function onRedirectAfterWrite(RedirectAfterWriteEvent $event): void
    {
        if ($event->entityClass !== Department::class) {
            return;
        }

        $event->response = new RedirectResponse('/custom-target');
    }
}
```

```yaml
# config/services.yaml
services:
    App\Event\Listener\RedirectToDepartmentListener:
        tags:
            - { name: kernel.event_listener, event: Dontdrinkandroot\CrudAdminBundle\Event\RedirectAfterWriteEvent, method: onRedirectAfterWrite }
```
