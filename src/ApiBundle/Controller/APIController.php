<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Контроллер от которого наследуются все API контроллеры
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class APIController extends Controller
{
    const FORMAT = "json";
}
