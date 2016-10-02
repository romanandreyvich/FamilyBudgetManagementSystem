<?php

namespace ApiBundle\Services;

use ApiBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Класс для реализация аутентификации пользователя в API
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class APITokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     *
     * @return array|mixed|null
     */
    public function getCredentials(Request $request)
    {
        $token = $request->get('access_token');
        if (!$token && !$request->get('user_id')) {
            return null;
        }

        $parameters = $request->request->all();
        if (empty($parameters)) {
            $parameters = $request->query->all();
        }

        return [
            'access_token' => $token,
            'user_id' => $request->get('user_id'),
            'parameters' => $parameters,
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return array|mixed|null
     * @internal param Request $request
     *
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!isset($credentials['access_token']) || !isset($credentials['user_id'])) {
            return null;
        }

        $user = $this->em->getRepository(User::class)->find($credentials['user_id']);

        if (!$user)
            return null;
        else
            return $user;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     *
     * @return array|mixed|null
     * @internal param Request $request
     *
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $accessToken = $credentials['access_token'];

        if ($accessToken !== $user->getSalt()) {
            return false;
        }

        $this->user = $user;

        return true;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return array|mixed|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $response = new Response(
            $exception->getMessage(),
            Response::HTTP_FORBIDDEN,
            ['content-type' => 'text/html']
        );

        return $response;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     *
     * @return array|mixed|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * @internal param Request $request
     *
     * @return array|mixed|null
     */
    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $authException
     *
     * @return array|mixed|null
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return false;
    }
}
