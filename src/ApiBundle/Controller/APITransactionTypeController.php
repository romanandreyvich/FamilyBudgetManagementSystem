<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\TransactionType;
use ApiBundle\Exception\APINotFoundException;
use ApiBundle\FormType\TransactionTypeFormType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Exception\APIIncorrectParametersException;
use ApiBundle\Traits\APIResponsible;

/**
 * Контроллер принимает запросы связанные с типами транзакций
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 * @Route("/api")
 */
class APITransactionTypeController extends APIController
{
    use APIResponsible;

    /**
     * Метод transaction/type/create - позволяет создать тип транзакции (например: приход, расход).
     *
     * @Route("/transaction/type/create", name="api_create_transaction_type", options = { "expose" = true })
     * @param Request $request
     *
     * @return Response
     *
     * @throws APIIncorrectParametersException
     */
    public function createTransactionTypeAction(Request $request)
    {
        $transactionType = new TransactionType();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(TransactionTypeFormType::class, $transactionType);

        $data = [
            'name' => $request->get('name')
        ];

        $form->submit($data);

        if ($form->isValid()) {
            $em->persist($transactionType);
            $em->flush();
        } else {
            throw new APIIncorrectParametersException();
        }

        return $this->getResponse(['transactionType' => $transactionType], $this->get('serializer'));
    }

    /**
     * Метод transaction/type/remove - позволяет удалить тип транзакции.
     *
     * @Route("/transaction/type/remove", name="api_remove_transaction_type")
     * @param Request $request
     *
     * @return Response
     *
     * @throws APIIncorrectParametersException
     */
    public function removeTransactionType(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $transactionType = $em->getRepository(TransactionType::class)->find($request->get('transactionType_id'));
        if ($transactionType) {
            $em->remove($transactionType);
            $em->flush();
        } else {
            throw new APINotFoundException();
        }

        return $this->getResponse([], $this->get('serializer'));
    }

    /**
     * Метод для получения типа траназкции
     *
     * @Route("/transaction/type", name="api_transaction_type", options = { "expose" = true })
     * @param Request $request
     *
     * @return Response
     */
    public function transactionTypeAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $result = null;

        if ($request->get('transaction_type_id')) {
            $transactionType = $em->getRepository(TransactionType::class)->find($request->get('transaction_type_id'));
            if (!$transactionType) {
                throw new APINotFoundException();
            }
            $result = $transactionType;
        }

        if (!$result) {
            $result = $em->getRepository(TransactionType::class)->findAll();
        }

        return $this->getResponse($result, $this->get('serializer'));
    }
}
