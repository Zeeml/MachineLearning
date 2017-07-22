<?php

namespace Zeeml\MachineLearning;

use Zeeml\DataSet\DataSet;
use Zeeml\MachineLearning\Exceptions\WrongUsageException;
use Zeeml\MachineLearning\Traits\MLHash;

class ML
{
    use MLHash;
    protected $algorithms;
    protected $learningRate;
    protected $dataSet;
    protected $epochsNumber;
    protected $epochsCollections;

    /**
     * ML constructor.
     * @param Dataset $dataSet
     * @throws \Exception
     */
    public function __construct(DataSet $dataSet)
    {
        $this->dataSet = $dataSet;
        $this->algorithms = [];
        $this->epochsNumber = 1;
    }

    public function using(array $algorithms): ML
    {
        $this->algorithms = $algorithms;

        return $this;
    }

    public function epochs(int $epochs): ML
    {
        if (empty($this->algorithms)) {
            throw new WrongUsageException('Please specify the algorithms before the epochs');
        }

        $this->epochsNumber = $epochs;

        return $this;
    }

    /**
     * fits the training dataSet
     * @param float $split
     * @param float $learningRate
     * @return ML
     * @throws WrongUsageException
     */
    public function fit(float $split = 0.8, float $learningRate = 0): ML
    {
        if (empty($this->algorithms)) {
            throw new WrongUsageException("No algorithm specified");
        }

        $hash = $this->hash($this->algorithms, $this->epochsNumber, $learningRate, $split);
        $epochCollection = $this->epochsCollections[$hash] ?? new EpochCollection($this->algorithms, $this->dataSet, $this->epochsNumber, $learningRate, $split);

        if (! $epochCollection->isDone()) {
            $epochCollection->fit();
        }

        $this->epochsCollections[$hash] = $epochCollection;

        return $this;
    }
    
    public function test(): ML
    {
        return $this;
    }
    
    public function stats()
    {
        
    }
}
