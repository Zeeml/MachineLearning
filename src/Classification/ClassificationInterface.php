<?php

namespace Zeeml\Algorithms\Classification;

/**
 * Interface PredictionInterface
 * @package Zeeml\Algorithms\Prediction
 */
interface ClassificationInterface
{
    public function prepare(): ClassificationInterface;

    public function train(int $epochs = 1, float $learningRate = 0.0): ClassificationInterface;

    public function process($input);

    public function predict($input);
}
