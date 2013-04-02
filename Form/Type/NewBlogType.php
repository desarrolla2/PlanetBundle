<?php

/**
 * This file is part of the desarrolla2 proyect.
 * 
 * Copyright (c)
 * Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>  
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewBlogType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', 'text', array(
                    'required' => false,
                    'trim' => true,
                ))
                ->add('description', 'textarea', array(
                    'required' => false,
                    'trim' => true,
                ))
                ->add('url', 'text', array(
                    'required' => true,
                    'trim' => true,
                ))
                ->add('rss', 'text', array(
                    'required' => false,
                    'trim' => true,
                ))
                ->add('userName', 'text', array(
                    'required' => true,
                    'trim' => true,
                ))
                ->add('userEmail', 'text', array(
                    'required' => true,
                    'trim' => true,
                ))
                ->add('captcha', 'captcha', array(
                    'distortion' => false,
                    'charset' => '1234567890',
                    'length' => 3,
                    'invalid_message' => 'Codigo erroneo',
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Desarrolla2\Bundle\PlanetBundle\Form\Model\NewBlogModel',
            'csrf_protection' => true,
        ));
    }

    public function getName() {
        return 'NewBlog';
    }

}
