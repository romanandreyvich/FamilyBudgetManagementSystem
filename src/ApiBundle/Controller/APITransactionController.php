<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Transaction;
use ApiBundle\Exception\APINotFoundException;
use ApiBundle\FormType\TransactionFormType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use ApiBundle\Exception\APIIncorrectParametersException;
use ApiBundle\Entity\User;
use ApiBundle\Traits\APIResponsible;

/**
 * Контроллер принимает запросы связанные с транзакциями.
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 * @Route("/api")
 */
class APITransactionController extends APIController
{
    use APIResponsible;

    /**
     * Метод transaction/create - позволяет создать транзакцию пользователя.
     *
     * @Route("/transaction/create", name="api_create_transaction", options = { "expose" = true })
     * @param Request $request
     *
     * @return Response
     *
     * @throws APIIncorrectParametersException
     */
    public function createTransactionAction(Request $request)
    {
        $transaction = new Transaction();
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(TransactionFormType::class, $transaction,
            [
                'category' => $request->get('category'),
                'user' => $request->get('user_id')
            ]
        );

        $data = [
            'asset' => $request->get('asset'),
            'category' => $request->get('category'),
            'user' => $request->get('user_id')
        ];

        $form->submit($data);

        if ($form->isValid()) {
            $em->persist($transaction);
            $em->flush();
        } else {
            throw new APIIncorrectParametersException();
        }

        return $this->getResponse(['transaction' => $transaction], $this->get('serializer'));
    }

    /**
     * Метод получения транзакций
     *
     * @Route("/transactions", name="api_transactions", options = { "expose" = true })
     * @param Request $request
     *
     * @return Response
     */
    public function transactionsAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $result = null;

        if ($request->get('user')) {
            $user = $em->getRepository(User::class)->find($request->get('user'));
            if (!$user) {
                throw new APINotFoundException();
            }
            $result = $user->getTransactions();
        }

        if ($request->get('transaction_id')) {
            $transaction = $em->getRepository(Transaction::class)->find($request->get('transaction_id'));
            if (!$transaction) {
                throw new APINotFoundException();
            }
            $result = $transaction;
        }

        if (!$result) {
            $result = $em->getRepository(Transaction::class)->findAll();
        }

        return $this->getResponse($result, $this->get('serializer'));
    }
}
