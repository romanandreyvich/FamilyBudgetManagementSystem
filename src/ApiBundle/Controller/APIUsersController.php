<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Family;
use ApiBundle\Entity\User;
use ApiBundle\Exception\APINotFoundException;
use ApiBundle\FormType\FamilyType;
use ApiBundle\FormType\UserType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Exception\APIIncorrectParametersException;
use ApiBundle\Traits\APIResponsible;

/**
 * Контроллер принимает запросы связанные с созданием/удалением/редактированием пользователей.
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 * @Route("/api")
 */
class APIUsersController extends APIController
{
    use APIResponsible;

    /**
     * Метод user/create - позволяет создать пользователя системы.
     *
     * @Route("/user/create", name="api_create_user")
     * @param Request $request
     *
     * @return Response
     *
     * @throws APIIncorrectParametersException
     */
    public function createUserAction(Request $request)
    {
        $user = new User();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(UserType::class, $user, ['family' => $request->get('family', null)]);

        $data = [
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'family' => $request->get('family')
        ];

        $form->submit($data);

        if ($form->isValid() && $request->get('password')) {
            $user->setEnabled(true);
            $user->setPlainPassword($request->get('password'));

            $em->persist($user);
            $em->flush();
        } else {
            throw new APIIncorrectParametersException();
        }

        return $this->getResponse(['user' => $user], $this->get('serializer'));
    }

    /**
     * Метод /family/create - позволяет создать семью в системе.
     *
     * @Route("/family/create", name="api_create_family", options = { "expose" = true })
     * @param Request $request
     *
     * @return Response
     *
     * @throws APIIncorrectParametersException
     */
    public function createFamilyAction(Request $request)
    {
        $family = new Family();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(FamilyType::class, $family);

        $data = [
            'lastname' => $request->get('lastname')
        ];

        $form->submit($data);

        if ($form->isValid()) {
            $em->persist($family);
            $em->flush();
        } else {
            throw new APIIncorrectParametersException();
        }

        return $this->getResponse(['family' => $family], $this->get('serializer'));
    }

    /**
     * Метод /family/set - добавить пользователя к семье.
     *
     * @Route("/family/set", name="api_set_family", options = { "expose" = true })
     * @param Request $request
     *
     * @return Response
     *
     * @throws APIIncorrectParametersException
     */
    public function setFamilyAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($request->get('current_user'));
        if ($user) {
            $user->setFamily($em->getReference(Family::class, $request->get('family_id')));
            $em->persist($user);
            $em->flush();
        } else {
            throw new APINotFoundException();
        }

        return $this->getResponse(['family' => $user->getFamily()], $this->get('serializer'));
    }

    /**
     * Метод /user/edit - позволяет редактировать данные пользователя системы.
     *
     * @Route("/user/edit", name="api_edit_user")
     * @param Request $request
     *
     * @return Response
     *
     * @throws APIIncorrectParametersException
     */
    public function editUserAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($request->get('current_user'));

        $form = $this->createForm(UserType::class, $user, ['family' => $request->get('family', null)]);

        $data = [
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'family' => $request->get('family')
        ];

        $form->submit($data);

        if ($form->isValid() && $request->get('password')) {
            $user->setPlainPassword($request->get('password'));
            $em->persist($user);
            $em->flush();
        } else {
            throw new APIIncorrectParametersException();
        }

        return $this->getResponse([], $this->get('serializer'));
    }

    /**
     * Метод /user/remove - позволяет удалить пользователя.
     *
     * @Route("/user/remove", name="api_remove_user")
     * @param Request $request
     *
     * @return Response
     *
     * @throws APIIncorrectParametersException
     */
    public function removeUserAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($request->get('current_user'));
        if ($user) {
            $em->remove($user);
            $em->flush();
        } else {
            throw new APINotFoundException();
        }

        return $this->getResponse([], $this->get('serializer'));
    }

    /**
     * Метод для получения семьи
     *
     * @Route("/families", name="api_families", options = { "expose" = true })
     * @param Request $request
     *
     * @return Response
     */
    public function familiesAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $result = null;

        if ($request->get('user')) {
            $user = $em->getRepository(User::class)->find($request->get('user_id'));
            if (!$user) {
                throw new APINotFoundException();
            }
            $result = $user->getFamily();
        }

        if ($request->get('family_id')) {
            $family = $em->getRepository(Family::class)->find($request->get('family_id'));
            if (!$family) {
                throw new APINotFoundException();
            }
            $result = $family;
        }

        if (!$result) {
            $result = $em->getRepository(Family::class)->findAll();
        }

        return $this->getResponse($result, $this->get('serializer'));
    }
}
