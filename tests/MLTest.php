<?php

namespace Zeeml\MachineLearning;

use PHPUnit\Framework\TestCase;
use Zeeml\Algorithms\Prediction\Linear\LinearRegression;
use Zeeml\Algorithms\Prediction\Logistic\LogisticRegression;
use Zeeml\DataSet\DataSet\Mapper;
use Zeeml\DataSet\DataSetFactory;

class MLTest extends TestCase
{

    /**
     * @test
     * @expectedException \Zeeml\MachineLearning\Exceptions\WrongUsageException
     */
    public function fitting_without_algorithms_should_fail()
    {
        $dataSet = DataSetFactory::create([[1, 2]]);
        $dataSet->prepare(new Mapper([0], [1]));

        $ml = new ML($dataSet);
        $ml->fit(0.8);
    }


    public function should_be_able_to_fit()
    {
        $dataSet = DataSetFactory::create([[1, 2]]);
        $dataSet->prepare(new Mapper([0], [1]));

        $ml = new ML($dataSet);
        $ml->using([LinearRegression::class, LogisticRegression::class])->epoch(3)->fit(0.8, 0.001);
    }
}
