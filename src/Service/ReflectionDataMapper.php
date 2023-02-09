<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use App\Entity\Job;
use Closure;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\StringUtils;
use PHPUnit\Framework\Assert;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\Core\DataAccessor\PropertyPathAccessor;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
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
    private ReflectionClass $reflectedClass;

    private PropertyPathAccessor $propertyPathAccessor;

    /**
     * @param class-string<T> $class
     */
    public function __construct(public readonly string $class)
    {
        $this->reflectedClass = new ReflectionClass($class);
        $this->propertyPathAccessor = new PropertyPathAccessor();
    }

    /**
     * @return Closure(FormInterface): T
     */
    public function getInstantiator(): Closure
    {
        return function (FormInterface $form) {
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
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $value = $parameter->getDefaultValue();
                }
                if (null === $value && !$parameter->allowsNull()) {
                    $type = self::getParameterType($parameter);
                    $value = match ($type->getName()) {
                        'int' => 0,
                        'float' => 0.0,
                        'string' => '',
                        'bool' => false,
                        'array' => [],
                        default => throw new RuntimeException(
                            sprintf(
                                'Cannot instantiate %s, parameter %s is not a builtin type',
                                $parameter->getDeclaringClass()?->getName() ?? 'n/a',
                                $parameter->getName()
                            )
                        )
                    };
                }
                $args[] = $value;
            }

            return $this->reflectedClass->newInstanceArgs($args);
        };
    }

    private static function getParameterType(ReflectionParameter|ReflectionProperty $parameter): ReflectionNamedType
    {
        if (!$parameter->hasType()) {
            throw new RuntimeException(
                sprintf(
                    'Cannot instantiate %s, parameter %s has no type and no default value',
                    $parameter->getDeclaringClass()?->getName() ?? 'n/a',
                    $parameter->getName()
                )
            );
        }
        $type = $parameter->getType();
        if (!$type instanceof ReflectionNamedType) {
            throw new RuntimeException(
                sprintf(
                    'Cannot instantiate %s, parameter %s has no type and no default value',
                    $parameter->getDeclaringClass()?->getName() ?? 'n/a',
                    $parameter->getName()
                )
            );
        }
        return $type;
    }

    /**
     * Similar to DataMapper, but if empty it sets the values to the default values of the constructor.
     *
     * {@inheritdoc}
     */
    public function mapDataToForms($viewData, Traversable $forms): void
    {
        $empty = null === $viewData || [] === $viewData;
        if (!$empty && !is_array($viewData) && !is_object($viewData)) {
            throw new UnexpectedTypeException($viewData, 'object, array or empty');
        }

        if ($empty) {
            $constructor = $this->reflectedClass->getConstructor();
            if (null === $constructor) {
                return;
            }
            $constructorParameters = array_reduce(
                $constructor->getParameters(),
                fn(array $carry, ReflectionParameter $parameter) => $carry + [$parameter->getName() => $parameter],
                []
            );

            /* Set default value from constructor for each form */
            foreach ($forms as $form) {
                $parameter = $constructorParameters[$form->getName()] ?? null;
                if (null === $parameter) {
                    continue;
                }
                if (!$parameter->isDefaultValueAvailable()) {
                    continue;
                }
                $form->setData($parameter->getDefaultValue());
            }
            return;
        }

        foreach ($forms as $form) {
            $config = $form->getConfig();

            if (!$empty && $config->getMapped() && $this->propertyPathAccessor->isReadable($viewData, $form)) {
                $form->setData($this->propertyPathAccessor->getValue($viewData, $form));
            } else {
                $form->setData($config->getData());
            }
        }
    }

    /**
     * Similar to DataMapper, but it does not set the data if it has not changed for readonly properties (excluding default) of if it is not nullable and the current value is the default value.
     *
     * {@inheritdoc}
     */
    public function mapFormsToData(Traversable $forms, &$viewData): void
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
                    if (
                        $property->isReadOnly()
                    ) {
                        if ($currentValue === $value) {
                            continue;
                        }

                        if (
                            !$this->allowsNull($property)
                            && null === $value
                            && $currentValue === $this->resolveDefaultForType($property->getType())
                        ) {
                            continue;
                        }

                    } elseif (
                        !$this->allowsNull($property)
                        && (null === $value)
                    ) {
                        $value = $this->resolveDefaultForType($property->getType());
                    }
                }

                $this->propertyPathAccessor->setValue($viewData, $value, $form);
            }
        }
    }

    private function resolveDefaultForType(ReflectionNamedType $type): mixed
    {
        return match ($type->getName()) {
            'int' => 0,
            'float' => 0.0,
            'string' => '',
            'bool' => false,
            'array' => [],
            default => throw new RuntimeException(
                sprintf(
                    'Cannot resolve default for type %s, parameter is not a builtin type',
                    $type->getName()
                )
            )
        };
    }

    private function allowsNull(ReflectionProperty|ReflectionParameter $property): bool
    {
        return self::getParameterType($property)->allowsNull();
    }
}
