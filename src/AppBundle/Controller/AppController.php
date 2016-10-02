<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Контроллер для обработки запроса главной страницы
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class AppController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle::index.html.twig', [
            'user' => $this->getUser(),
            'from' => new \DateTime('-1 month'),
            'to' => new \DateTime('+1 day')
        ]);
    }
}
