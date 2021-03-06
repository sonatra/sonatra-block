<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Block\Extension\Core\Type;

use Fxp\Component\Block\AbstractType;
use Fxp\Component\Block\BlockBuilderInterface;
use Fxp\Component\Block\Extension\Core\DataTransformer\MoneyToLocalizedStringTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class MoneyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
        $builder->resetViewTransformers();

        $builder->addViewTransformer(
            new MoneyToLocalizedStringTransformer(
                $options['precision'],
                $options['grouping'],
                $options['rounding_mode'],
                $options['locale'],
                $options['currency'],
                $options['divisor']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'currency' => 'EUR',
            'divisor' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return NumberType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'money';
    }
}
