<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Класс IncorrectParameterException
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class APIIncorrectParametersException extends BadRequestHttpException implements APIExceptionInterface
{
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message?: 'Invalid request parameters !', $previous, $code);
    }
}
