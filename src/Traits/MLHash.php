<?php

namespace Zeeml\MachineLearning\Traits;

trait MLHash
{
    /**
     * calculates the hash of an Epoch Collection upon creation
     * @param array $algorithms
     * @param int $nbEpochs
     * @param float $learningRate
     * @param float $split
     * @return string
     */
    private function hash(array $algorithms, int $nbEpochs, float $learningRate, float $split): string
    {
        return hash(
            'sha256',
            sprintf('algos:%s;split:%s;epochs:%s;learnRate:%s', implode(',', $algorithms) , $split, $nbEpochs, $learningRate),
            false
        );
    }
}