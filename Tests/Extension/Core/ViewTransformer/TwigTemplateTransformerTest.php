<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Block\Tests\Extension\Core\ViewTransformer;

use Fxp\Component\Block\Extension\Core\ViewTransformer\TwigTemplateTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TwigTemplateTransformerTest extends TestCase
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $template;

    protected function setUp()
    {
        $twig = $this->getMockBuilder('Twig_Environment')->disableOriginalConstructor()->getMock();
        $this->template = $this->getMockForAbstractClass('\Twig_Template', [$twig], '', true, true, true, ['render', 'renderBlock']);

        $twig->expects($this->any())
            ->method('loadTemplate')
            ->will($this->returnValue($this->template));

        $this->twig = $twig;
    }

    protected function tearDown()
    {
        $this->twig = null;
        $this->template = null;
    }

    public function testTransformResource()
    {
        $this->template->expects($this->any())
            ->method('render')
            ->will($this->returnValue('RESOURCE_RENDER'));

        $transformer = new TwigTemplateTransformer($this->twig, 'resource');

        $this->assertEquals('RESOURCE_RENDER', $transformer->transform(null));
    }

    public function testTransformResourceBlock()
    {
        $this->template->expects($this->any())
            ->method('renderBlock')
            ->will($this->returnValue('RESOURCE_BLOCK_RENDER'));

        $transformer = new TwigTemplateTransformer($this->twig, 'resource', 'block');

        $this->assertEquals('RESOURCE_BLOCK_RENDER', $transformer->transform(null));
    }
}
