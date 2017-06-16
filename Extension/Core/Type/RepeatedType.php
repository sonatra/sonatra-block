<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Block\Extension\Core\Type;

use Sonatra\Component\Block\AbstractType;
use Sonatra\Component\Block\BlockBuilderInterface;
use Sonatra\Component\Block\Extension\Core\DataTransformer\ValueToDuplicatesTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class RepeatedType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new ValueToDuplicatesTransformer(array(
            $options['first_name'],
            $options['second_name'],
        )));
        $builder
            ->add($options['first_name'], $options['type'], array_merge($options['options'], $options['first_options']))
            ->add($options['second_name'], $options['type'], array_merge($options['options'], $options['second_options']))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'type' => TextType::class,
            'options' => array(),
            'first_options' => array(),
            'second_options' => array(),
            'first_name' => 'first',
            'second_name' => 'second',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FieldType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'repeated';
    }
}