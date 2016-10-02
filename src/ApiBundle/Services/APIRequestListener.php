<?php

namespace ApiBundle\Services;

use ApiBundle\Exception\APIAccessDeniedException;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * EventListener для проверки наличия данных для авторизации пользователя
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class APIRequestListener
{
    protected $serializer;

    public function __construct(Serializer $serializer = null)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return null|Response
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (substr($request->getRequestUri(), 0, 4) != "/api" || $request->getRequestUri() == "/api/doc") {
            return null;
        }

        if (!$request->get('access_token') || !$request->get('user_id')) {
            throw new APIAccessDeniedException();
        }

        return null;
    }
}
