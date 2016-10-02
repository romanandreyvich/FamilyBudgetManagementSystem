<?php

namespace ApiBundle\Services;

use ApiBundle\Entity\Family;
use ApiBundle\Exception\APINotFoundException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Сервис создающий отчеты
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class TransactionsReport
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getStatisticsByDate(Request $request)
    {

        $family = $this->em->getRepository(Family::class)->find($request->get('family'));
        $result = [];

        if (!$family) {
            throw new APINotFoundException();
        }

        $currentDate = new \DateTime($request->get('to'));
        $minimalDate = new \DateTime($request->get('from'));

        foreach ($family->getUsers() as $user) {
            $userTransaction = [];
            foreach ($user->getTransactions() as $transaction) {
                if ($transaction->getTime() <= $currentDate && $transaction->getTime() >= $minimalDate) {
                    $userTransaction[] = [
                        'asset' => $transaction->getAsset(),
                        'category' => $transaction->getCategory()->getName(),
                        'datetime' => $transaction->getTime(),
                        'type' => $transaction->getCategory()->getType()->getName(),
                    ];
                }
            }

            $result[] = [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'transactions' => $userTransaction
            ];
        }

        return $result;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getComparisonStatistics(Request $request)
    {
        $family = $this->em->getRepository(Family::class)->find($request->get('family'));
        $result = [];

        if (!$family) {
            throw new APINotFoundException();
        }

        foreach ($family->getUsers() as $user) {
            $userTransaction = [];
            $compare = [];
            foreach ($user->getTransactions() as $transaction) {
                if (!isset($compare[$transaction->getCategory()->getType()->getName()])) {
                    $compare[$transaction->getCategory()->getType()->getName()] = 0;
                }

                $userTransaction[] = [
                    'asset' => $transaction->getAsset(),
                    'category' => $transaction->getCategory()->getName(),
                    'datetime' => $transaction->getTime(),
                    'type' => $transaction->getCategory()->getType()->getName(),
                ];

                $compare[$transaction->getCategory()->getType()->getName()] += $transaction->getAsset();
            }

            $result[$user->getId()] = [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'transactions' => $userTransaction,
                'compare' => $compare
            ];
        }

        return $result;
    }
}
