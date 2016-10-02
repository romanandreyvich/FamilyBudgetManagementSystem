<?php

namespace ApiBundle\FormType;

use ApiBundle\Entity\TransactionType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Класс формы TransactionCategory
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class TransactionCategoryFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $options['type'];

        $builder
            ->add(
                'name'
            )
            ->add(
                'type', EntityType::class, [
                    'class' => TransactionType::class,
                    'required' => true,
                    'query_builder' => function (EntityRepository $er) use ($type) {
                        return $er->createQueryBuilder('t')
                            ->where('t.id = :id')
                            ->setParameter(':id', $type);
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
                'data_class' => 'ApiBundle\Entity\TransactionCategory',
                'csrf_protection' => false,
                'type' => null
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'transaction_category_type';
    }
}
