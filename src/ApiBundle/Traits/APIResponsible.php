<?php

namespace ApiBundle\Traits;

use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Controller\APIController;
use JMS\Serializer\Serializer;

/**
 * Трейт для генерации Response ответа.
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
trait APIResponsible
{
    /**
     * @param $elements
     * @param Serializer $serializer
     *
     * @return Response
     * @internal param $errors
     */
    function getResponse($elements, Serializer $serializer)
    {
        $response = [];
        $response['response_code'] = Response::HTTP_OK;
        $response['result'] = $elements;
        $body = $serializer->serialize($response, APIController::FORMAT);

        return new Response($body, Response::HTTP_OK, ['Content-Type' => 'application/' . APIController::FORMAT]);
    }
}
