<?php

namespace Zeeml\MachineLearning;

use Zeeml\DataSet\DataSet;
use Zeeml\DataSet\DataSetFactory;
use Zeeml\MachineLearning\Exceptions\WrongUsageException;
use Zeeml\DataSet\Exception\WrongUsageException as WrongDataSetUsageException;
use Zeeml\MachineLearning\Traits\Process;

/**
 * Class EpochCollection
 * @package Zeeml\Algorithms
 */
class EpochCollection implements \Iterator
{
    use Process;

    protected $position;
    protected $epochs;

    protected $trainingSet;
    protected $testSet;
    protected $split;
    protected $learningRate;

    /**
     * Functions that creates all the needed epochs
     * @param array $algorithms
     * @param DataSet $dataSet
     * @param int $nbEpochs
     * @param float $learningRate
     * @param float $split
     * @return EpochCollection
     * @throws WrongUsageException
     */
    public function __construct(array $algorithms, DataSet $dataSet, int $nbEpochs = 1, float $learningRate = 0.0, float $split = 0.8)
    {
        $this
            ->clear()
            ->setSplit($split)
            ->createDataSets($dataSet)
            ->setLearningRate($learningRate)
            ->createEpochs($algorithms, $nbEpochs)
        ;
    }

    /**
     * Fits every epoch of the collection with hte list of algorithms
     */
    public function fit()
    {
        $this->busy();
        foreach ($this->epochs as $epoch) {
            $epoch->fit($this->trainingSet, $this->learningRate);
        }
        $this->done();
    }

    /**
     * Init the epoch collection buy emptying the epochs
     */
    private function clear(): EpochCollection
    {
        $this->position = 0;
        $this->epochs = [];

        return $this;
    }

    /**
     * returns the last epoch
     * @return Epoch|null
     */
    public function getLastEpoch(): Epoch
    {
        return $this->epochs[count($this->epochs) - 1];
    }

    /**
     * sets the split of the dataSet
     * $split% will be used for training, (1 - $split%) will be used for test
     * $split must be in interval ]0, 1]
     * @param $split
     * @throws WrongUsageException
     * @return EpochCollection
     */
    private function setSplit($split): EpochCollection
    {
        $this->split = $split;

        return $this;
    }

    /**
     * sets the learning rate
     * @param float $learningRate
     * @return EpochCollection;
     */
    private function setLearningRate(float $learningRate): EpochCollection
    {
        $this->learningRate = $learningRate;

        return $this;
    }

    /**
     * Creates the Epochs
     * @param array $algorithms
     * @param int $nbEpochs
     */
    private function createEpochs(array $algorithms, int $nbEpochs)
    {
        $nbEpochs = $nbEpochs < 1 ? 1 : $nbEpochs;
        for ($i = 0; $i < $nbEpochs; $i ++) {
            $epoch = new Epoch($algorithms, $i, $this->learningRate);
            $previousEpoch = end($this->epochs);
            if ($previousEpoch instanceof Epoch) {
                $previousEpoch->setNext($epoch);
                $epoch->setPrevious($previousEpoch);
            }

            $this->epochs[] = $epoch;
        }
    }

    /**
     * function that creates the training and test dataSet based on the specified split
     * @param DataSet $dataSet
     * @return EpochCollection
     * @throws WrongUsageException
     */
    private function createDataSets(DataSet $dataSet): EpochCollection
    {
        if (!$dataSet->isPrepared()) {
            throw new WrongUsageException('DataSet must be prepared.');
        }

        try {
            list($this->trainingSet, $this->testSet) = DataSetFactory::splitDataSet($dataSet, $this->split);
        } catch (WrongDataSetUsageException $e) {
            throw new WrongUsageException($e->getMessage());
        }

        return $this;
    }

    /**
     * Returns the dataSet used from the training
     * @return DataSet
     */
    public function getTrainingSet(): DataSet
    {
        return $this->trainingSet;
    }

    /**
     * Returns the dataSet used from the training
     * @return DataSet
     */
    public function getTestSet(): DataSet
    {
        return $this->testSet;
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
