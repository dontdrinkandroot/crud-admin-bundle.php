<?php

namespace Dontdrinkandroot\CrudAdminBundle\Event\Listener;

use Doctrine\ORM\Query;
use Dontdrinkandroot\Common\Asserted;
use Knp\Component\Pager\Event\ItemsEvent;
use Knp\Component\Pager\Event\Subscriber\Filtration\Doctrine\ORM\Query\WhereWalker;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Prefixes and Postfixes filter and sortable requests with wildcards by default and sets the case insensitive flag.
 */
class FilterAndSortableListener
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function onItems(ItemsEvent $event): void
    {
        $request = $this->requestStack->getMainRequest() ?? new Request();

        if ($event->target instanceof Query) {
            $query = $event->target;
            $value = Asserted::stringOrNull(
                $request->query->get($event->options[PaginatorInterface::FILTER_VALUE_PARAMETER_NAME])
            );
            if (null !== $value) {
                $value = '%' . $value . '%';
                $query->setHint(WhereWalker::HINT_PAGINATOR_FILTER_CASE_INSENSITIVE, true);
                $request->query->set($event->options[PaginatorInterface::FILTER_VALUE_PARAMETER_NAME], $value);
            }

            $sortFieldParameter = $event->options[PaginatorInterface::SORT_FIELD_PARAMETER_NAME];
            if (
                null !== $sortFieldParameter
                && null !== ($sortField = $request->query->get($sortFieldParameter))
                && is_string($sortField)
            ) {
                if (!str_contains($sortField, '.')) {
                    $sortField = 'entity.' . $sortField;
                }
                $request->query->set($sortFieldParameter, $sortField);
            }
        }
    }
}
