<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Block;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
trait CommonTypeTrait
{
    /**
     * {@inheritdoc}
     */
    public function buildBlock(BlockBuilderInterface $builder, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function finishBlock(BlockBuilderInterface $builder, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function addParent(BlockInterface $parent, BlockInterface $block, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function removeParent(BlockInterface $parent, BlockInterface $block, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(BlockInterface $child, BlockInterface $block, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
