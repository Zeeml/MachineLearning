<?php

namespace Zeeml\MachineLearning;

use Zeeml\Dataset\Dataset;
use Zeeml\Algorithms\Prediction\LinearRegression;

class ML
{
    protected $algorithms;
    
    protected $dataset;
    
    protected $split;
    
    public function __construct(array $algorithms, Dataset $dataset, float $split = 0.8)
    {
        foreach ($algorithms as $algorithmClass) {
            $this->algorithms[$algorithmClass] = new $algorithmClass;
        }
        
        $this->dataset = $dataset;
        $this->split = $this->setSplit($split);
    }
    
    public function setSplit(float $split)
    {
        if ($split <= 0 || $split > 1) {
            throw new \Exception("Split value must be greater than 0 and lower than 1");
        }
        $this->split = $split;
        
        return $this;
    }
    
    public function fit()
    {
        foreach ($this->algorithms as $algorithm) {
            $algorithm->fit($this->split);
        }
        
        return $this;
    }
    
    public function test()
    {
        foreach ($this->algorithms as $algorithm) {
            $algorithm->test(1 - $this->split);
        }
    
        return $this;
    }
    
    public function stats()
    {
        
    }
}
