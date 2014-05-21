<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BlockBundle\Twig\Block;

use Sonatra\Bundle\BlockBundle\Block\BlockRendererEngineInterface;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface TwigRendererEngineInterface extends BlockRendererEngineInterface
{
    /**
     * Sets Twig's environment.
     *
     * @param \Twig_Environment $environment
     */
    public function setEnvironment(\Twig_Environment $environment);
}
