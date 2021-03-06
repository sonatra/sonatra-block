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

use Fxp\Component\Block\Exception\UnexpectedTypeException;
use Fxp\Component\Block\Guess\Guess;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class BlockTypeGuesserChain implements BlockTypeGuesserInterface
{
    private $guessers = [];

    /**
     * Constructor.
     *
     * @param BlockTypeGuesserInterface[] $guessers Guessers as instances of BlockTypeGuesserInterface
     *
     * @throws UnexpectedTypeException if any guesser does not implement BlockTypeGuesserInterface
     */
    public function __construct(array $guessers)
    {
        foreach ($guessers as $guesser) {
            if (!$guesser instanceof BlockTypeGuesserInterface) {
                throw new UnexpectedTypeException($guesser, 'Fxp\Component\Block\BlockTypeGuesserInterface');
            }

            if ($guesser instanceof self) {
                $this->guessers = array_merge($this->guessers, $guesser->guessers);
            } else {
                $this->guessers[] = $guesser;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function guessType($class, $property)
    {
        return $this->guess(function (BlockTypeGuesserInterface $guesser) use ($class, $property) {
            return $guesser->guessType($class, $property);
        });
    }

    /**
     * Executes a closure for each guesser and returns the best guess from the
     * return values.
     *
     * @param \Closure $closure The closure to execute. Accepts a guesser
     *                          as argument and should return a Guess instance
     *
     * @return Guess The guess with the highest confidence
     */
    protected function guess(\Closure $closure)
    {
        $guesses = [];

        foreach ($this->guessers as $guesser) {
            if ($guess = $closure($guesser)) {
                $guesses[] = $guess;
            }
        }

        return Guess::getBestGuess($guesses);
    }
}
