<?php

namespace Zeeml\MachineLearning;

use PHPUnit\Framework\TestCase;
use Zeeml\Algorithms\Prediction\Linear\LinearRegression;
use Zeeml\Algorithms\Prediction\Logistic\LogisticRegression;
use Zeeml\DataSet\DataSet;
use Zeeml\DataSet\DataSetFactory;

class EpochCollectionTest extends TestCase
{
    /**
     * @test
     * @expectedException \Zeeml\MachineLearning\Exceptions\WrongUsageException
     */
    public function unprepared_dataset_fails()
    {
        $dataSet = DataSetFactory::create([[1, 2]]);
        new EpochCollection([LinearRegression::class], $dataSet, 10, 0, 1.1);
    }

    /**
     * @test
     * @expectedException \Zeeml\MachineLearning\Exceptions\WrongUsageException
     */
    public function creating_collection_with_split_greater_than_one_fails()
    {
        $dataSet = DataSetFactory::create([[1, 2]]);
        $dataSet->prepare(new DataSet\Mapper([0], [1]));

        new EpochCollection([LinearRegression::class], $dataSet, 10, 0, 1.1);
    }

    /**
     * @test
     * @expectedException \Zeeml\MachineLearning\Exceptions\WrongUsageException
     */
    public function creating_collection_with_negative_split_fails()
    {
        $dataSet = DataSetFactory::create([[1, 2]]);
        $dataSet->prepare(new DataSet\Mapper([0], [1]));

        new EpochCollection([LinearRegression::class], $dataSet, 10, 0, -1);
    }

    /**
     * @test
     * @expectedException \Zeeml\MachineLearning\Exceptions\WrongUsageException
     */
    public function creating_collection_when_split_equals_zero_fails()
    {
        $dataSet = DataSetFactory::create([[1, 2]]);
        $dataSet->prepare(new DataSet\Mapper([0], [1]));

        new EpochCollection([LinearRegression::class], $dataSet, 10, 0, 0);
    }

    /**
     * @test
     * @expectedException \Zeeml\MachineLearning\Exceptions\WrongUsageException
     */
    public function should_create_epochs()
    {
        $dataSet = DataSetFactory::create([[1, 2]]);
        $dataSet->prepare(new DataSet\Mapper([0], [1]));

        $epochCollection = new EpochCollection([LinearRegression::class], $dataSet, 10, 0, 0);

        $this->assertInstanceOf(\Iterator::class, $epochCollection);
        $this->assertEquals(10, $epochCollection);
    }

    public function should_be_iterable_on_epochs()
    {
        $dataSet = DataSetFactory::create([[1, 2]]);
        $dataSet->prepare(new DataSet\Mapper([0], [1]));

        $epochCollection = new EpochCollection([LinearRegression::class], $dataSet, 10, 0, 0);

        $this->assertInstanceOf(\Iterator::class, $epochCollection);

        foreach ($epochCollection as $epoch) {
            $this->assertInstanceOf(Epoch::class, $epoch);
        }

    }

    /**
     * @test
     */
    public function should_be_able_to_return_last_epoch()
    {
        $dataSet = DataSetFactory::create([[1, 2]]);
        $dataSet->prepare(new DataSet\Mapper([0], [1]));

        $epochCollection = new EpochCollection([LinearRegression::class], $dataSet, 10, 0, 0.5);
        $this->assertEquals($epochCollection->getLastEpoch()->getNumber(), 9);
    }

    /**
     * @test
     */
    public function should_create_trainingSet_from_split()
    {
        $dataSet = DataSetFactory::create([[1, 2], [3, 4], [5, 6], [7, 8]]);
        $dataSet->prepare(new DataSet\Mapper([0], [1]));

        $epochCollection = new EpochCollection([LinearRegression::class], $dataSet, 10, 0, 0.8);

        $this->assertEquals(4, $dataSet->getSize());
        $this->assertEquals(3, $epochCollection->getTrainingSet()->getSize());
        $this->assertEquals(1, $epochCollection->getTestSet()->getSize());
    }

    /**
     * @test
     */
    public function should_fit_every_epoch_with_all_algorithms()
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

        $epochCollection = new EpochCollection([LinearRegression::class, LogisticRegression::class], $dataSet, 10, 0, 1);

        $this->assertFalse($epochCollection->isBusy());
        $this->assertFalse($epochCollection->isDone());

        $epochCollection->fit();

        $this->assertFalse($epochCollection->isBusy());
        $this->assertTrue($epochCollection->isDone());

        foreach ($epochCollection as $epoch) {
            $linearRegression = $epoch->getAlgorithm(LinearRegression::class);
            $this->assertEquals([0.4, 0.8], $linearRegression->getCoefficients());
        }

    }
}
