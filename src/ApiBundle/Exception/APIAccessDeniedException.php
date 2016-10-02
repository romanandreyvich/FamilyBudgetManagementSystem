<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Класс AccessDeniedException
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class APIAccessDeniedException extends AccessDeniedHttpException implements APIExceptionInterface
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message?: 'Access denied !', $previous, $code);
    }
}
