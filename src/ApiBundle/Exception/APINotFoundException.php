<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Класс NotFoundException
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class APINotFoundException extends NotFoundHttpException implements APIExceptionInterface
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $previous, $code);
    }
}
