<?php

namespace Zeeml\Algorithms\Prediction;

use Zeeml\Algorithms\EpochCollection;
use Zeeml\Dataset\DatasetInterface;

/**
 * Class AbstractPrediction
 * @package Zeeml\Algorithms\Prediction
 */
abstract class AbstractPrediction implements PredictionInterface
{
    protected $dataset;
    protected $epochCollection;

    /**
     * AbstractPrediction constructor.
     * @param DatasetInterface $dataset
     */
    public function __construct(DatasetInterface $dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * @return DatasetInterface
     */
    public function getDataset(): DatasetInterface
    {
        return $this->dataset;
    }

    /**
     * @return EpochCollection
     */
    public function getEpochCollection(): EpochCollection
    {
        return $this->epochCollection;
    }
}
