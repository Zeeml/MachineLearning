<?php

namespace Zeeml\MachineLearning\Tests;

use PHPUnit\Framework\TestCase;
use Zeeml\Algorithms\Prediction\Linear\LinearRegression;
use Zeeml\Algorithms\Prediction\Logistic\LogisticRegression;
use Zeeml\DataSet\DataSet;
use Zeeml\DataSet\DataSetFactory;
use Zeeml\MachineLearning\Epoch;
use Zeeml\MachineLearning\ML;

class EpochTest extends TestCase
{
    /**
     * @test
     */
    public function new_epoch_should_only_store_algorithms()
    {
        $epoch = new Epoch([LinearRegression::class, DataSet::class], 1);

        $algorithms = $epoch->getAlgorithms();
        $this->assertCount(1, $algorithms);
        $this->assertArrayHasKey(LinearRegression::class, $algorithms);
        $this->assertEquals($epoch->getNumber(), 1);
    }

    /**
     * @test
     * @expectedException \Zeeml\MachineLearning\Exceptions\WrongUsageException
     */
    public function new_epoch_without_any_algorithm_fails()
    {
        $epoch = new Epoch([ML::class, DataSet::class], 1);
    }

    /**
     * @test
     */
    public function epochs_can_be_chained()
    {
        $epoch1 = new Epoch([LinearRegression::class], 1);
        $epoch2 = new Epoch([LinearRegression::class], 2);
        $epoch3 = new Epoch([LinearRegression::class], 3);

        $epoch1->setNext($epoch2);
        $epoch2->setNext($epoch3);
        $epoch2->setPrevious($epoch1);
        $epoch3->setPrevious($epoch2);

        $this->assertNull($epoch1->previous());
        $this->assertInstanceOf(Epoch::class, $epoch1->next());
        $this->assertEquals(2, $epoch1->next()->getNumber());

        $this->assertInstanceOf(Epoch::class, $epoch2->next());
        $this->assertInstanceOf(Epoch::class, $epoch2->previous());
        $this->assertEquals(3, $epoch2->next()->getNumber());
        $this->assertEquals(1, $epoch2->previous()->getNumber());

        $this->assertNull($epoch3->next());
        $this->assertInstanceOf(Epoch::class, $epoch3->previous());
        $this->assertEquals(2, $epoch3->previous()->getNumber());
    }

    /**
     * @test
     */
    public function each_epoch_has_a_number()
    {
        $epoch1 = new Epoch([LinearRegression::class], 1);
        $epoch2 = new Epoch([LinearRegression::class], 2);
        $epoch3 = new Epoch([LinearRegression::class], 1);
        $epoch4 = new Epoch([LinearRegression::class], 11);

        $this->assertEquals(1, $epoch1->getNumber());
        $this->assertEquals(2, $epoch2->getNumber());
        $this->assertEquals(1, $epoch3->getNumber());
        $this->assertEquals(11, $epoch4->getNumber());
    }

    /**
     * @test
     */
    public function fit_should_fit_every_algorithm()
    {
        $data = [
            [1, 1],
            [2, 3],
            [4, 3],
            [3, 2],
            [5, 5],
        ];

        $dataSet = DataSetFactory::create($data);
        $dataSet->prepare(new DataSet\Mapper([0], [1]));

        $epoch = new Epoch([LinearRegression::class, LogisticRegression::class], 1);
        $epoch->fit($dataSet, 0);

        $this->assertCount(2, $epoch->getAlgorithms());
        $this->assertInstanceOf(LinearRegression::class, $epoch->getAlgorithm(LinearRegression::class));
        $this->assertInstanceOf(LogisticRegression::class, $epoch->getAlgorithm(LogisticRegression::class));

        $this->assertEquals([0.4, 0.8], $epoch->getAlgorithm(LinearRegression::class)->getCoefficients());
    }
}
