<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BlockBundle\Block;

use Sonatra\Bundle\BlockBundle\Block\Extension\Core\Type\BlockType;
use Sonatra\Bundle\BlockBundle\Block\Util\StringUtil;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class AbstractType extends AbstractCommonType implements BlockTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return StringUtil::fqcnToBlockPrefix(get_class($this));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BlockType::class;
    }
}
