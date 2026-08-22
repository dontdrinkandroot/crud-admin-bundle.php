<?php

namespace Dontdrinkandroot\CrudAdminBundle\Exception;

use RuntimeException;

/**
 * Thrown when no field renderer supports the given field definition and value.
 */
class NoRendererFoundException extends RuntimeException
{
}
