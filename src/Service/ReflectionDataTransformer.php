<?php

namespace Dontdrinkandroot\CrudAdminBundle\Service;

use App\Entity\Job;
use Closure;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormInterface;
use Traversable;

/**
 * @template T of object
 */
class ReflectionDataTransformer implements DataMapperInterface
{
    /**
     * @var ReflectionClass<T>
     */
    private ReflectionClass $reflectedClass;

    /**
     * @param class-string<T> $class
     */
    public function __construct(public readonly string $class)
    {
        $this->reflectedClass = new ReflectionClass($class);
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

    private static function getParameterType(ReflectionParameter $parameter): ReflectionNamedType
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
     * {@inheritdoc}
     */
    public function mapDataToForms($viewData, Traversable $forms)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData(Traversable $forms, &$viewData)
    {
        return;
    }
}
