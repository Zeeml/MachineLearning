<?php

namespace Zeeml\MachineLearning;

use Zeeml\Algorithms\AlgorithmsInterface;
use Zeeml\DataSet\DataSet;
use Zeeml\MachineLearning\Exceptions\WrongUsageException;
use Zeeml\MachineLearning\Traits\Process;

/**
 * Class Epoch
 * @package Zeeml\Algorithms
 */
class Epoch
{
    use Process;

    protected $epoch;
    protected $algorithms;
    protected $previous;
    protected $next;
    protected $hash;

    /**
     * Epoch constructor.
     * @param $algorithms $algorithms
     * @param int $epoch
     */
    public function __construct(array $algorithms, int $epoch)
    {
        $this->setAlgorithms($algorithms);
        $this->epoch = $epoch;
    }

    /**
     * trains the epoch using the algorithm
     * @param DataSet $dataSet
     * @param float $learningRate
     */
    public function fit(DataSet $dataSet, float $learningRate)
    {
        $this->busy();

        foreach ($this->algorithms as $algorithm) {
            $previousAlgorithm = $this->previous() ? $this->previous()->getAlgorithm(get_class($algorithm)) : null;
            $algorithm->fit($dataSet, $learningRate, $previousAlgorithm);
        }

        $this->done();
    }

    /**
     * tests the epoch using the algorithm
     * @param DataSet $dataset
     */
    public function test(DataSet $dataset)
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
     * returns the algorithms used
     * @return AlgorithmsInterface
     */
    public function getAlgorithms()
    {
        return $this->algorithms;
    }

    /**
     * returns the specific algorithm if any
     * @return AlgorithmsInterface
     */
    public function getAlgorithm($algorithmName)
    {
        return $this->algorithms[$algorithmName] ?? null;
    }

    public function setAlgorithms(array $algorithms)
    {
        foreach ($algorithms as $algorithm) {
            if (class_exists($algorithm) && isset(class_implements($algorithm)[AlgorithmsInterface::class])) {
                $this->algorithms[$algorithm] = new $algorithm;
            }
        }

        if (empty($this->algorithms)) {
            throw new WrongUsageException('No algorithm specified');
        }
    }

    /**
     * Returns the epoch's number
     * @return int
     */
    public function getNumber(): int
    {
        return $this->epoch;
    }
}
