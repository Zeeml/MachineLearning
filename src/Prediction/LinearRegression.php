<?php

namespace Zeeml\Algorithms\Prediction;

use Zeeml\Algorithms\AbstractAlgorithm;
use Zeeml\Algorithms\Algorithms\Factory\AlgorithmsFactory;
use Zeeml\Algorithms\Epoch;
use Zeeml\Algorithms\EpochCollection;
use Zeeml\Algorithms\Exceptions\BadArgumentException;
use Zeeml\Algorithms\Exceptions\UnknownErrorException;

/**
 * Class LinearRegression
 * class that trains a set of data following the linear regression method
 * @package Zeeml\Algorithms\Adapter
 */
class LinearRegression extends AbstractPrediction
{
    /**
     * prepare the dataset
     * @return PredictionInterface
     */
    public function prepare(): PredictionInterface
    {
        $this->dataset->prepare(1, 1);

        return $this;
    }


    public function normalize(): PredictionInterface
    {
        // TODO: Implement normalize() method.
    }

    public function standardize(): PredictionInterface
    {
        // TODO: Implement standardize() method.
    }

    /**
     * Trains the dataset using a specific algorithm for 1 or many epochs
     * @param int $epochs
     * @param float $learningRate
     * @return PredictionInterface
     * @throws BadArgumentException
     */
    public function train(int $epochs = 1, float $learningRate = 0.0): PredictionInterface
    {
        //If epochs is negatif or if the user specifies more than one epoch but no learning and exception is thrown
        if ($epochs < 1 || ($epochs > 1 && $learningRate == 0)) {
            throw new BadArgumentException('Error in setting epochs/learning rate');
        }
        if ($epochs === 1 && $learningRate == 0) {
            //If there is only one epoch and no learning rate is specified, the simple linear algorithm will be used
            $algorithm = AlgorithmsFactory::simpleLinearRegressionAlgorithm();
        } else {
            //If the learning rate is specified, the stochastic linear regression is used
            $algorithm = AlgorithmsFactory::SGDLinearRegressionAlgorithm();
        }

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
