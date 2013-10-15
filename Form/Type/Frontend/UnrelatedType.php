<?php

/**
 * This file is part of the planetubuntu proyect.
 *
 * Copyright (c)
 * Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Form\Type\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnrelatedType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'captcha',
                'captcha',
                array(
                    'distortion' => false,
                    'charset' => '1234567890',
                    'length' => 3,
                    'invalid_message' => 'Codigo erroneo',
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Desarrolla2\Bundle\PlanetBundle\Form\Model\Frontend\UnrelatedModel',
                'csrf_protection' => true,
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Unrelated';
    }
}
