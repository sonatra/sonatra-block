<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BlockBundle\Block\Extension\Core\Type;

use Sonatra\Bundle\BlockBundle\Block\AbstractType;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class HiddenType extends AbstractType
{
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
        return 'hidden';
    }
}
