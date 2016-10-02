<?php

namespace ApiBundle\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Класс формы TransactionType
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class TransactionTypeFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name'
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'ApiBundle\Entity\TransactionType',
                'csrf_protection' => false,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'transactionType_type';
    }
}
