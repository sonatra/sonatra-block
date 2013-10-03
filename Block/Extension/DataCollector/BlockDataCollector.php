<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\BlockBundle\Block\Extension\DataCollector;

use Sonatra\Bundle\BlockBundle\Block\BlockInterface;
use Sonatra\Bundle\BlockBundle\Block\BlockView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Data collector for {@link \Sonatra\Bundle\BlockBundle\Block\BlockInterface} instances.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class BlockDataCollector extends DataCollector implements BlockDataCollectorInterface
{
    /**
     * @var BlockDataExtractor
     */
    private $dataExtractor;

    /**
     * Stores the collected data per {@link BlockInterface} instance.
     *
     * Uses the hashes of the blocks as keys. This is preferrable over using
     * {@link \SplObjectStorage}, because in this way no references are kept
     * to the {@link BlockInterface} instances.
     *
     * @var array
     */
    private $dataByBlock;

    /**
     * Stores the collected data per {@link BlockView} instance.
     *
     * Uses the hashes of the views as keys. This is preferrable over using
     * {@link \SplObjectStorage}, because in this way no references are kept
     * to the {@link BlockView} instances.
     *
     * @var array
     */
    private $dataByView;

    /**
     * Connects {@link BlockView} with {@link BlockInterface} instances.
     *
     * Uses the hashes of the views as keys and the hashes of the blocks as
     * values. This is preferrable over storing the objects directly, because
     * this way they can safely be discarded by the GC.
     *
     * @var array
     */
    private $blocksByView;

    /**
     * Constructor.
     *
     * @param BlockDataExtractorInterface $dataExtractor
     */
    public function __construct(BlockDataExtractorInterface $dataExtractor)
    {
        $this->dataExtractor = $dataExtractor;
        $this->data = array(
            'blocks' => array(),
        );
    }

    /**
     * Does nothing. The data is collected during the block event listeners.
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function associateBlockWithView(BlockInterface $block, BlockView $view)
    {
        $this->blocksByView[spl_object_hash($view)] = spl_object_hash($block);
    }

    /**
     * {@inheritdoc}
     */
    public function collectConfiguration(BlockInterface $block)
    {
        $hash = spl_object_hash($block);

        if (!isset($this->dataByBlock[$hash])) {
            $this->dataByBlock[$hash] = array();
        }

        $this->dataByBlock[$hash] = array_replace(
            $this->dataByBlock[$hash],
            $this->dataExtractor->extractConfiguration($block)
        );

        foreach ($block as $child) {
            $this->collectConfiguration($child);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectDefaultData(BlockInterface $block)
    {
        $hash = spl_object_hash($block);

        if (!isset($this->dataByBlock[$hash])) {
            $this->dataByBlock[$hash] = array();
        }

        $this->dataByBlock[$hash] = array_replace(
            $this->dataByBlock[$hash],
            $this->dataExtractor->extractDefaultData($block)
        );

        foreach ($block as $child) {
            $this->collectDefaultData($child);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function collectViewVariables(BlockView $view)
    {
        $hash = spl_object_hash($view);

        if (!isset($this->dataByView[$hash])) {
            $this->dataByView[$hash] = array();
        }

        $this->dataByView[$hash] = array_replace(
            $this->dataByView[$hash],
            $this->dataExtractor->extractViewVariables($view)
        );

        foreach ($view->children as $child) {
            $this->collectViewVariables($child);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildPreliminaryBlockTree(BlockInterface $block)
    {
        $this->data['blocks'][$block->getName()] = array();

        $this->recursiveBuildPreliminaryBlockTree($block, $this->data['blocks'][$block->getName()]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildFinalBlockTree(BlockInterface $block, BlockView $view)
    {
        $this->data['blocks'][$block->getName()] = array();

        $this->recursiveBuildFinalBlockTree($block, $view, $this->data['blocks'][$block->getName()]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'sonatra_block';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    private function recursiveBuildPreliminaryBlockTree(BlockInterface $block, &$output = null)
    {
        $hash = spl_object_hash($block);

        $output = isset($this->dataByBlock[$hash])
            ? $this->dataByBlock[$hash]
            : array();

        $output['children'] = array();

        foreach ($block as $name => $child) {
            $output['children'][$name] = array();

            $this->recursiveBuildPreliminaryBlockTree($child, $output['children'][$name]);
        }
    }

    private function recursiveBuildFinalBlockTree(BlockInterface $block = null, BlockView $view, &$output = null)
    {
        $viewHash = spl_object_hash($view);
        $blockHash = null;

        if (null !== $block) {
            $blockHash = spl_object_hash($block);
        } elseif (isset($this->blocksByView[$viewHash])) {
            // The BlockInterface instance of the CSRF token is never contained in
            // the BlockInterface tree of the block, so we need to get the
            // corresponding BlockInterface instance for its view in a different way
            $blockHash = $this->blocksByView[$viewHash];
        }

        $output = isset($this->dataByView[$viewHash])
            ? $this->dataByView[$viewHash]
            : array();

        if (null !== $blockHash) {
            $output = array_replace(
                $output,
                isset($this->dataByBlock[$blockHash])
                    ? $this->dataByBlock[$blockHash]
                    : array()
            );
        }

        $output['children'] = array();

        foreach ($view->children as $name => $childView) {
            // The CSRF token, for example, is never added to the block tree.
            // It is only present in the view.
            $childBlock = null !== $block && $block->has($name)
                ? $block->get($name)
                : null;

            $output['children'][$name] = array();

            $this->recursiveBuildFinalBlockTree($childBlock, $childView, $output['children'][$name]);
        }
    }
}