<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\TransactionCategory;
use ApiBundle\Exception\APINotFoundException;
use ApiBundle\FormType\TransactionCategoryFormType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Exception\APIIncorrectParametersException;
use ApiBundle\Traits\APIResponsible;

/**
 * Контроллер принимает запросы связанные с категориями транзакций.
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 * @Route("/api")
 */
class APITransactionCategoryController extends APIController
{
    use APIResponsible;

    /**
     * Метод transaction/category/create - позволяет создать категорию транзакции (например: нашел на улице, зар. плата и т.д.).
     *
     * @Route("/transaction/category/create", name="api_create_transaction_category", options = { "expose" = true })
     * @param Request $request
     *
     * @return Response
     *
     * @throws APIIncorrectParametersException
     */
    public function createTransactionCategoryAction(Request $request)
    {
        $transactionCategory = new TransactionCategory();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(TransactionCategoryFormType::class, $transactionCategory, ['type' => $request->get('type', null)]);

        $data = [
            'name' => $request->get('name'),
            'type' => $request->get('type')
        ];

        $form->submit($data);

        if ($form->isValid()) {
            $em->persist($transactionCategory);
            $em->flush();
        } else {
            throw new APIIncorrectParametersException();
        }

        return $this->getResponse(['transactionCategory' => $transactionCategory], $this->get('serializer'));
    }

    /**
     * Метод transaction/category/remove - позволяет удалить категорию транзакции.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws APIIncorrectParametersException
     */
    public function removeTransactionCategory(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(TransactionCategory::class)->find($request->get('transactionCategory_id'));
        if ($user) {
            $em->remove($user);
            $em->flush();
        } else {
            throw new APINotFoundException();
        }

        return $this->getResponse([], $this->get('serializer'));
    }

    /**
     * Метод для получения категорий транзакций
     *
     * @Route("/transaction/category", name="api_transaction_category", options = { "expose" = true })
     * @param Request $request
     *
     * @return Response
     */
    public function transactionCategoryAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $result = null;

        if ($request->get('transaction_category_id')) {
            $transactionCategory = $em->getRepository(TransactionCategory::class)->find($request->get('transaction_category_id'));
            if (!$transactionCategory) {
                throw new APINotFoundException();
            }
            $result = $transactionCategory;
        }

        if (!$result) {
            $result = $em->getRepository(TransactionCategory::class)->findAll();
        }

        return $this->getResponse($result, $this->get('serializer'));
    }
}
