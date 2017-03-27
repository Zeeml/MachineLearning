<?php

namespace Zeeml\Algorithms;

use Zeeml\Algorithms\Algorithms\AlgorithmsInterface;

/**
 * Class EpochCollection
 * @package Zeeml\Algorithms
 */
class EpochCollection implements \Iterator
{
    protected $position;
    protected $epochs;

    /**
     * Functions that creates all the needed epochs
     * @param AlgorithmsInterface $algorithm
     * @param int $nbEpochs
     * @param float $learningRate
     * @return EpochCollection
     */
    public static function collectionFactory(AlgorithmsInterface $algorithm, int $nbEpochs = 1, float $learningRate = 0.0): EpochCollection
    {
        $epochsCollection = new self();
        $epochsCollection->clear();
        $nbEpochs = $nbEpochs < 1 ? 1 : $nbEpochs;

        for ($i = 0; $i < $nbEpochs; $i ++) {
            $epoch = new Epoch(clone $algorithm, $i, $learningRate);
            $epochsCollection->addEpoch($epoch);
        }

        return $epochsCollection;
    }

    /**
     * Init the epoch collection buy emptying the epochs
     */
    public function clear()
    {
        $this->position = 0;
        $this->epochs = [];
    }

    /**
     * add a nex epoch to the collection
     * @param Epoch $epoch
     * @return EpochCollection
     */
    public function addEpoch(Epoch $epoch): EpochCollection
    {
        $previousEpoch = end($this->epochs);
        if ($previousEpoch instanceof Epoch) {
            $previousEpoch->setNext($epoch);
            $epoch->setPrevious($previousEpoch);
        }

        $this->epochs[] = $epoch;

        return $this;
    }

    /**
     * returns the last epoch
     * @return Epoch|null
     */
    public function getLastEpoch()
    {
        return $this->epochs[count($this->epochs) - 1]?? null;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->epochs[$this->position];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->epochs[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
