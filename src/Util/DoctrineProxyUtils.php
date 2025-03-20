<?php

namespace Dontdrinkandroot\CrudAdminBundle\Util;

use Doctrine\Persistence\Proxy;

class DoctrineProxyUtils
{
    /**
     * @template T of object
     * @param class-string<Proxy<T>>|class-string<T> $className
     * @return class-string<T>
     */
    public static function getRealClass(string $className): string
    {
        $pos = strrpos($className, '\\' . Proxy::MARKER . '\\');

        /** @var class-string<T> */
        return (false === $pos)
            ? $className
            : substr($className, $pos + Proxy::MARKER_LENGTH + 2);
    }

    /**
     * @template T of object
     * @param Proxy<T>|T $object
     * @return class-string<T>
     */
    public static function getClass(object $object): string
    {
        return self::getRealClass(get_class($object));
    }
}
