<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use Closure;
use Dontdrinkandroot\Common\Asserted;
use Override;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\DataAccessor\PropertyPathAccessor;
use Symfony\Component\Form\FormInterface;
use Traversable;

use function is_array;
use function is_object;

/**
 * @template T of object
 */
class ReflectionDataMapper implements DataMapperInterface
{
    /**
     * @var ReflectionClass<T>
     */
    private readonly ReflectionClass $reflectedClass;

    private readonly PropertyPathAccessor $propertyPathAccessor;

    /**
     * @param class-string<T> $class
     */
    public function __construct(public readonly string $class)
    {
        $this->reflectedClass = new ReflectionClass($class);
        $this->propertyPathAccessor = new PropertyPathAccessor();
    }

    /**
     * @param array<string,mixed> $customDefaults
     * @return Closure(FormInterface): T
     */
    public function getInstantiator(array $customDefaults = []): Closure
    {
        return function (FormInterface $form) use ($customDefaults): object {
            $constructor = $this->reflectedClass->getConstructor();
            if (null === $constructor) {
                return $this->reflectedClass->newInstance();
            }

            $parameters = $constructor->getParameters();

            $args = [];
            foreach ($parameters as $parameter) {
                $parameterName = $parameter->getName();
                $value = null;
                if ($form->has($parameterName)) {
                    $value = $form->get($parameterName)->getData();
                } elseif (array_key_exists($parameterName, $customDefaults)) {
                    $value = $customDefaults[$parameterName];
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $value = $parameter->getDefaultValue();
                }

                if (null === $value && !$parameter->allowsNull()) {
                    $type = self::getType($parameter);
                    $value = self::resolveDefaultForType($type);
                }
                $args[] = $value;
            }

            return $this->reflectedClass->newInstanceArgs($args);
        };
    }

    /**
     * Similar to DataMapper, but if empty it sets the values to the default values of the constructor.
     *
     * @param Traversable<FormInterface> $forms
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    #[Override]
    public function mapDataToForms(mixed $viewData, Traversable $forms): void
    {
        $empty = null === $viewData || [] === $viewData;
        if (!$empty && !is_array($viewData) && !is_object($viewData)) {
            throw new UnexpectedTypeException($viewData, 'object, array or empty');
        }

        if ($empty) {
            $propertiesWithDefaults = array_filter(
                $this->reflectedClass->getProperties(),
                fn(ReflectionProperty $property): bool => $property->hasDefaultValue()
            );
            $constructorArgumentsWithDefaults = array_filter(
                $this->reflectedClass->getConstructor()?->getParameters() ?? [],
                    fn(ReflectionParameter $parameter): bool => $parameter->isDefaultValueAvailable()
            );
            $namesWithDefaultValues = array_reduce(
                array_merge(
                    $propertiesWithDefaults,
                    $constructorArgumentsWithDefaults
                ),
                fn(array $carry, ReflectionProperty|ReflectionParameter $property): array => array_merge(
                    $carry,
                    [$property->getName() => $property->getDefaultValue()]
                ),
                []
            );

            foreach ($forms as $form) {
                $formName = $form->getName();
                if (array_key_exists($formName, $namesWithDefaultValues)) {
                    $form->setData($namesWithDefaultValues[$formName]);
                }
            }
            return;
        }

        foreach ($forms as $form) {
            $config = $form->getConfig();

            if ($config->getMapped() && $this->propertyPathAccessor->isReadable($viewData, $form)) {
                $form->setData($this->propertyPathAccessor->getValue($viewData, $form));
            } else {
                $form->setData($config->getData());
            }
        }
    }

    /**
     * Similar to DataMapper, but it does not set the data if it has not changed for readonly properties (excluding default) of if it is not nullable and the current value is the default value.
     *
     * @param Traversable<FormInterface> $forms
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    #[Override]
    public function mapFormsToData(Traversable $forms, mixed &$viewData): void
    {
        if (null === $viewData) {
            return;
        }

        if (!is_array($viewData) && !is_object($viewData)) {
            throw new UnexpectedTypeException($viewData, 'object, array or empty');
        }

        foreach ($forms as $form) {
            $config = $form->getConfig();
            if ($config->getMapped()
                && $form->isSubmitted()
                && $form->isSynchronized()
                && !$form->isDisabled()
                && $this->propertyPathAccessor->isWritable($viewData, $form)
            ) {
                $formName = Asserted::nonEmptyString($form->getName());
                $value = $form->getData();
                $currentValue = $this->propertyPathAccessor->getValue($viewData, $form);

                if (
                    $this->reflectedClass->hasProperty($formName)
                ) {
                    $property = $this->reflectedClass->getProperty($formName);
                    $type = self::getType($property);
                    if (
                        $property->isReadOnly()
                    ) {
                        if ($currentValue === $value) {
                            continue;
                        }

                        if (
                            !$type->allowsNull()
                            && null === $value
                            && $currentValue === self::resolveDefaultForType($type)
                        ) {
                            continue;
                        }
                    } elseif (
                        !$type->allowsNull()
                        && null === $value
                    ) {
                        $value = self::resolveDefaultForType($type);
                    }
                }

                $this->propertyPathAccessor->setValue($viewData, $value, $form);
            }
        }
    }

    private static function resolveDefaultForType(ReflectionNamedType $type): mixed
    {
        return match ($type->getName()) {
            'int' => 0,
            'float' => 0.0,
            'string' => '',
            'bool' => false,
            'array' => [],
            default => throw new RuntimeException(
                sprintf(
                    'Cannot resolve default for type %s, only builtin types are supported',
                    $type->getName()
                )
            )
        };
    }

    private static function getType(ReflectionParameter|ReflectionProperty $parameter): ReflectionNamedType
    {
        $type = $parameter->getType();
        if (!$type instanceof ReflectionNamedType) {
            throw new RuntimeException(
                sprintf(
                    'Only reflection named types are supported, got %s',
                    null !== $type ? $type::class : 'null'
                )
            );
        }

        return $type;
    }
}
