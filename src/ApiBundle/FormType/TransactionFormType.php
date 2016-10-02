<?php

namespace ApiBundle\FormType;

use ApiBundle\Entity\TransactionCategory;
use ApiBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Класс формы Transaction
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class TransactionFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $category = $options['category'];
        $user = $options['user'];

        $builder
            ->add(
                'asset'
            )
            ->add(
                'category', EntityType::class, [
                    'class' => TransactionCategory::class,
                    'required' => false,
                    'empty_data' => null,
                    'query_builder' => function (EntityRepository $er) use ($category) {
                        return $er->createQueryBuilder('c')
                            ->where('c.id = :id')
                            ->setParameter(':id', $category);
                    }
                ]
            )
            ->add(
                'user', EntityType::class, [
                    'class' => User::class,
                    'required' => false,
                    'empty_data' => null,
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        return $er->createQueryBuilder('u')
                            ->where('u.id = :id')
                            ->setParameter(':id', $user);
                    }
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'ApiBundle\Entity\Transaction',
                'csrf_protection' => false,
                'category' => null,
                'user' => null
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'transaction_type';
    }
}
