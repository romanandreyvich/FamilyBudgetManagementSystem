<?php

namespace ApiBundle\Command;

use ApiBundle\Entity\Family;
use ApiBundle\Entity\Transaction;
use ApiBundle\Entity\TransactionCategory;
use ApiBundle\Entity\TransactionType;
use ApiBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда для загрузки тестового набора данных
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class FixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fixtures:test');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $transactionType = new TransactionType();
        $transactionType->setName('Приход');
        $em->persist($transactionType);

        $transactionType2 = new TransactionType();
        $transactionType2->setName('Расход');
        $em->persist($transactionType2);

        $transactionCategory = new TransactionCategory();
        $transactionCategory
            ->setName('Зарплата')
            ->setType($transactionType);
        $em->persist($transactionCategory);

        $transactionCategory2 = new TransactionCategory();
        $transactionCategory2
            ->setName('Найдено на улице')
            ->setType($transactionType);
        $em->persist($transactionCategory2);

        $transactionCategory3 = new TransactionCategory();
        $transactionCategory3
            ->setName('Ипотека')
            ->setType($transactionType2);
        $em->persist($transactionCategory3);

        $transactionCategory4 = new TransactionCategory();
        $transactionCategory4
            ->setName('Покупка продуктов')
            ->setType($transactionType2);
        $em->persist($transactionCategory4);

        $family = new Family();
        $family->setLastname('Петровы');
        $em->persist($family);

        $user = new User();
        $user
            ->setUsername('roman')
            ->setEmail('roman@gmail.com')
            ->setPlainPassword('123')
            ->setEnabled(true)
            ->setFamily($family);
        $em->persist($user);

        $user2 = new User();
        $user2
            ->setUsername('anton')
            ->setEmail('anton@gmail.com')
            ->setPlainPassword('123')
            ->setEnabled(true)
            ->setFamily($family);
        $em->persist($user2);

        $transaction = new Transaction();
        $transaction
            ->setAsset('50000')
            ->setCategory($transactionCategory)
            ->setUser($user);
        $em->persist($transaction);

        $transaction2 = new Transaction();
        $transaction2
            ->setAsset('300')
            ->setCategory($transactionCategory2)
            ->setUser($user);
        $em->persist($transaction2);

        $transaction3 = new Transaction();
        $transaction3
            ->setAsset('15000')
            ->setCategory($transactionCategory3)
            ->setUser($user);
        $em->persist($transaction3);

        $transaction4 = new Transaction();
        $transaction4
            ->setAsset('5000')
            ->setCategory($transactionCategory4)
            ->setUser($user);
        $em->persist($transaction4);

        $transaction5 = new Transaction();
        $transaction5
            ->setAsset('40000')
            ->setCategory($transactionCategory)
            ->setUser($user2);
        $em->persist($transaction5);

        $transaction6 = new Transaction();
        $transaction6
            ->setAsset('1000')
            ->setCategory($transactionCategory2)
            ->setUser($user2);
        $em->persist($transaction6);

        $transaction7 = new Transaction();
        $transaction7
            ->setAsset('25000')
            ->setCategory($transactionCategory3)
            ->setUser($user2);
        $em->persist($transaction7);

        $transaction8 = new Transaction();
        $transaction8
            ->setAsset('4000')
            ->setCategory($transactionCategory4)
            ->setUser($user2);
        $em->persist($transaction8);

        $em->flush();

        $output->writeln("Тестовые данные созданы !");

        return 0;
    }
}
