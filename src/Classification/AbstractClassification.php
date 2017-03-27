<?php

namespace Zeeml\Algorithms\Classification;

use Zeeml\Algorithms\EpochCollection;
use Zeeml\Dataset\DatasetInterface;

/**
 * Class AbstractClassification
 * @package Zeeml\Algorithms\Classification
 */
abstract class AbstractClassification implements ClassificationInterface
{
    protected $dataset;
    protected $epochCollection;

    /**
     * Prediction Algorithms constructor.
     * AbstracPrediction constructor.
     * @param DatasetInterface $dataset
     */
    public function __construct(DatasetInterface $dataset)
    {
        $this->dataset = $dataset;
    }

    /**
     * returns the dataset
     * @return DatasetInterface
     */
    public function getDataset(): DatasetInterface
    {
        return $this->dataset;
    }

    /**
     * returns the epochCollection
     * @return EpochCollection
     */
    public function getEpochCollection(): EpochCollection
    {
        return $this->epochCollection;
    }
}
