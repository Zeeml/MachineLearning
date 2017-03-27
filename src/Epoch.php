<?php

namespace Zeeml\Algorithms;

use Zeeml\Algorithms\Algorithms\AlgorithmsInterface;
use Zeeml\Dataset\DatasetInterface;

/**
 * Class Epoch
 * @package Zeeml\Algorithms
 */
class Epoch
{
    const STATUS_CREATED = 'created';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_FINISHED = 'finished';

    protected $epoch;
    protected $learningRate;
    protected $status;
    protected $algorithm;
    protected $previous;
    protected $next;
    protected $hash;

    /**
     * Epoch constructor.
     * @param AlgorithmsInterface $algorithm
     * @param int $epoch
     * @param float $learningRate
     */
    public function __construct(AlgorithmsInterface $algorithm, int $epoch, float $learningRate)
    {
        $this->algorithm = $algorithm;
        $this->epoch = $epoch;
        $this->learningRate = $learningRate;
        $this->status = self::STATUS_CREATED;
    }

    /**
     * trains the epoch using the algorithm
     * @param DatasetInterface $dataset
     */
    public function train(DatasetInterface $dataset)
    {
        $this->busy();
        $this->algorithm->train($dataset, $this->learningRate, $this->previous()->algorithm?? null);
        $this->done();
    }

    /**
     * tests the epoch using the algorithm
     * @param DatasetInterface $dataset
     */
    public function test(DatasetInterface $dataset)
    {
        $this->busy();
        $this->algorithm->test($dataset);
        $this->done();
    }

    /**
     * predicts the epoch using the algorithm
     */
    public function process($input)
    {
        $this->busy();
        $this->algorithm->process($input);
        $this->done();
    }

    /**
     * returns the next epoch or null if there is no next epoch
     * @return Epoch|null
     */
    public function next()
    {
        return $this->next;
    }

    /**
     * the next epoch
     * @param Epoch $epoch
     */
    public function setNext(Epoch $epoch)
    {
        $this->next = $epoch;
    }

    /**
     * gets the previous epoch or null if no previous epoch
     * @return Epoch|null
     */
    public function previous()
    {
        return $this->previous;
    }

    /**
     * sets the previous epoch
     * @param Epoch $epoch
     */
    public function setPrevious(Epoch $epoch)
    {
        $this->previous = $epoch;
    }

    /**
     * flags the epoch as currently working
     */
    public function busy()
    {
        $this->status = self::STATUS_IN_PROGRESS;
    }

    /**
     * flags the epoch as work done
     */
    public function done()
    {
        $this->status = self::STATUS_FINISHED;
    }

    /**
     * returns the algorithms used
     * @return AlgorithmsInterface
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }
}
