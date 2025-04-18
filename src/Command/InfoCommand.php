<?php

namespace Dontdrinkandroot\CrudAdminBundle\Command;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\CrudControllerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldDefinition\FieldDefinitionsResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\RouteInfo\RouteInfoResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateResolverInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\TranslationDomain\TranslationDomainResolverInterface;
use Override;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('ddr:crud-admin:info')]
class InfoCommand extends Command
{
    final public const string ARGUMENT_ENTITY_CLASS = 'entity-class';

    /**
     * @param CrudControllerRegistry<object> $crudControllerRegistry
     */
    public function __construct(
        private readonly CrudControllerRegistry $crudControllerRegistry,
        private readonly TemplateResolverInterface $templateResolver,
        private readonly TranslationDomainResolverInterface $translationDomainResolver,
        private readonly RouteInfoResolverInterface $routeInfoResolver,
        private readonly FieldDefinitionsResolverInterface $fieldDefinitionsResolver
    ) {
        parent::__construct();
    }

    #[Override]
    protected function configure(): void
    {
        $this->addArgument(self::ARGUMENT_ENTITY_CLASS, InputArgument::OPTIONAL);
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var class-string|null $entityClass */
        $entityClass = $input->getArgument(self::ARGUMENT_ENTITY_CLASS);
        if (null !== $entityClass) {
            $controller = $this->crudControllerRegistry->findControllerByEntityClass($entityClass);
            if (null === $controller) {
                throw new RuntimeException('Entity class not found: '. $entityClass);
            }
            $controllersByEntityClass = [
                $entityClass => $controller
            ];
        } else {
            $controllersByEntityClass = $this->crudControllerRegistry->getControllersByEntityClass();
        }

        foreach ($controllersByEntityClass as $controllerEntityClass => $controller) {
            $output->writeln($controllerEntityClass . ":");
            $output->writeln("\t" . 'controller: ' . $controller::class);
            $output->writeln("\t" . 'routes:');
            foreach (CrudOperation::all() as $crudOperation) {
                $routeInfo = $this->routeInfoResolver->resolveRouteInfo($controllerEntityClass, $crudOperation);
                $output->write("\t\t" . $crudOperation->value . ":");
                if (null === $routeInfo) {
                    $output->writeln(' null');
                } else {
                    $output->writeln('');
                    $output->writeln(sprintf("\t\t\tname: %s", $routeInfo->name));
                    $output->writeln(sprintf("\t\t\tpath: %s", $routeInfo->path));
                }
            }
            $output->writeln("\t" . 'templates:');
            foreach (CrudOperation::all() as $crudOperation) {
                $template = $this->templateResolver->resolveTemplate($controllerEntityClass, $crudOperation);
                $output->writeln(sprintf("\t\t%s: %s", $crudOperation->value, $template ?? 'null'));
            }
            $output->writeln("\t" . 'translation_domains:');
            foreach (CrudOperation::all() as $crudOperation) {
                $translationDomain = $this->translationDomainResolver->resolveTranslationDomain($controllerEntityClass);
                $output->writeln(sprintf("\t\t%s: %s", $crudOperation->value, $translationDomain ?? 'null'));
            }
            $output->writeln("\t" . 'field_definitions:');
            foreach (CrudOperation::all() as $crudOperation) {
                $fieldDefinitions = $this->fieldDefinitionsResolver->resolveFieldDefinitions(
                    $controllerEntityClass,
                    $crudOperation
                );
                $output->write("\t\t" . $crudOperation->value . ":");
                if (null === $fieldDefinitions) {
                    $output->writeln(' null');
                } else {
                    $output->writeln('');
                    foreach ($fieldDefinitions as $fieldDefinition) {
                        $output->writeln(
                            sprintf(
                                "\t\t\t- { name: %s, type: %s, sortable: %s, filterable: %s }",
                                $fieldDefinition->propertyPath,
                                $fieldDefinition->displayType,
                                $fieldDefinition->sortable ? 'true' : 'false',
                                $fieldDefinition->filterable ? 'true' : 'false'
                            )
                        );
                    }
                }
            }
        }
        return Command::SUCCESS;
    }
}
