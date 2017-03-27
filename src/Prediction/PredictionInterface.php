<?php

namespace Zeeml\Algorithms\Prediction;

/**
 * Interface PredictionInterface
 * @package Zeeml\Algorithms\Prediction
 */
interface PredictionInterface
{
    public function prepare(): PredictionInterface;

    public function normalize(): PredictionInterface;

    public function standardize(): PredictionInterface;

    public function train(int $epochs = 1, float $learningRate = 0.0): PredictionInterface;

    public function process($input);

    public function predict($input);
}
