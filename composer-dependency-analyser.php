<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

return new Configuration()
    ->addPathToExclude(__DIR__ . '/src/Service/QueryBuilder/QueryBuilderExtensionProviderInterface.php')
    ->addPathToExclude(__DIR__ . '/src/Service/Persister/DoctrineItemPersisterProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Event/Listener/FilterAndSortableListener.php')
    ->addPathToExclude(__DIR__ . '/src/Event/Listener/PostPersistFlashListener.php')
    ->addPathToExclude(__DIR__ . '/src/Service/PaginationTarget/DoctrinePaginationTargetProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Service/Pagination/DefaultPaginationProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Service/FieldDefinition/DoctrineFieldDefinitionsProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Service/Id/DoctrineIdProvider.php')
    ->addPathToExclude(__DIR__ . '/src/Service/Query/QueryExtensionProviderInterface.php')
    ->addPathToExclude(__DIR__ . '/src/Service/Item/DoctrineItemProvider.php');
