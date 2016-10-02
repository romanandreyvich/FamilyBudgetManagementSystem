<?php

namespace ApiBundle\FormType;

use ApiBundle\Entity\Family;
use ApiBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Класс формы User
 *
 * @author Roman Belousov <romanandreyvich@gmail.com>
 */
class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $family = $options['family'];

        $builder
            ->add(
                'username'
            )
            ->add(
                'email'
            )
            ->add(
                'password'
            )
            ->add(
                'family', EntityType::class, [
                    'class' => Family::class,
                    'required' => false,
                    'empty_data' => null,
                    'query_builder' => function (EntityRepository $er) use ($family) {
                        return $er->createQueryBuilder('f')
                            ->where('f.id = :id')
                            ->setParameter(':id', $family);
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
                'data_class' => 'ApiBundle\Entity\User',
                'csrf_protection' => false,
                'family' => null
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user_type';
    }
}
