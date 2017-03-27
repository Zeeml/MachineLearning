<?php

namespace Zeeml\Algorithms\Prediction;

use Zeeml\Algorithms\Algorithms\Factory\AlgorithmsFactory;
use Zeeml\Algorithms\Classification\AbstractClassification;
use Zeeml\Algorithms\Classification\ClassificationInterface;

/**
 * Class LinearRegression
 * class that trains a set of data following the linear regression method
 * @package Zeeml\Algorithms\Adapter
 */
class LogisticRegression extends AbstractClassification
{
    /**
     * @return ClassificationInterface
     */
    public function prepare(): ClassificationInterface
    {
        $this->dataset->prepare(2, 1);

        return $this;
    }

    /**
     * Trains the dataset using a specific algorithm for 1 or many epochs
     * @param int $epochs
     * @param float $learningRate
     * @return ClassificationInterface
     */
    public function train(int $epochs = 1, float $learningRate = 0): ClassificationInterface
    {
        //instantiate the algorithm
        $algorithm = AlgorithmsFactory::LogisticRegressionAlgorithm();
        //create all the needed epochs with the selected algorithm and learning rate
        $this->epochCollection = EpochCollection::collectionFactory($algorithm, $epochs, $learningRate);
        foreach ($this->epochCollection as $epoch) {
            //trains the dataset for each epoch with the selected< algorithm
            $epoch->train($this->dataset);
            $epoch->test($this->dataset);
        }

        return $this;
    }

    /**
     *
     * @param $input
     * @return float
     * @throws UnknownErrorException
     */
    public function predict($input): float
    {
        $lastEpoch = $this->linearRegression->getEpochCollection()->getLastEpoch();
        if (! $lastEpoch instanceof Epoch) {
            throw new UnknownErrorException('No epoch found');
        }
        return $lastEpoch->process($input);
    }

    public function process($input)
    {
        return $this->predict($input);
    }
}
