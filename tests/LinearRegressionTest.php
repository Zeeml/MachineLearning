<?php

use Zeeml\Algorithms\Prediction\LinearRegression;
use Zeeml\Dataset\Dataset;
use PHPUnit\Framework\TestCase;
use Zeeml\Algorithms\Exceptions\BadArgumentException;
use Zeeml\Algorithms\EpochCollection;
use Zeeml\Algorithms\Epoch;
use Zeeml\Algorithms\Algorithms\SimpleLinearRegressionAlgorithm;

/**
 * Class LinearRegressionTest
 */
class LinearRegressionTest extends TestCase
{
    protected $linearRegression;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->linearRegression = null;

        parent::tearDown();
    }

    /**
     * Tests the prepare function that prepares the dataset
     * $simpleLinearRegression->prepare()
     */
    public function testPrepare()
    {
        $dataset = Dataset::factory(__DIR__ . '/fixtures/LinearRegressionExample.csv', Dataset::PREDICTION);
        $this->linearRegression = new LinearRegression($dataset);
        $this->linearRegression->prepare();
        $this->assertTrue(count($this->linearRegression->getDataset()->instances()[0]->inputs()) == 1);
    }

    /**
     *Tests that the training with 0 epochs fails
     */
    public function testTrainFailNoEpoch()
    {
        $dataset = Dataset::factory(__DIR__ . '/fixtures/LinearRegressionExample.csv', Dataset::PREDICTION);
        $this->linearRegression = new LinearRegression($dataset);
        $this->linearRegression->prepare();
        $this->expectException(BadArgumentException::class);

        $this->linearRegression->train(0);
    }

    /**
     *Tests that the training with more than one epoch but no learning rate fails
     */
    public function testTrainFailEpochsWithoutLearningRate()
    {
        $dataset = Dataset::factory(__DIR__ . '/fixtures/LinearRegressionExample.csv', Dataset::PREDICTION);
        $this->linearRegression = new LinearRegression($dataset);
        $this->linearRegression->prepare();
        $this->expectException(BadArgumentException::class);

        $this->linearRegression->train(5);
    }


    /**
     * Tests the train with valid data, checks the results of the training for validation
     */
    public function testTrain()
    {
        $dataset = Dataset::factory(__DIR__ . '/fixtures/LinearRegressionExample.csv', Dataset::PREDICTION);
        $this->linearRegression = new LinearRegression($dataset);
        $this->linearRegression->prepare();
        $this->assertTrue($this->linearRegression->train() instanceof LinearRegression);
        $this->assertTrue($this->linearRegression->getEpochCollection() instanceof EpochCollection);
        $lastEpoch = $this->linearRegression->getEpochCollection()->getLastEpoch();
        $this->assertTrue($lastEpoch instanceof Epoch);
        $this->assertTrue($lastEpoch->getAlgorithm() instanceof SimpleLinearRegressionAlgorithm);

        $dataset = Dataset::factory(__DIR__ . '/fixtures/LinearRegressionStochasticExample.csv', Dataset::PREDICTION);
        $this->linearRegression = new LinearRegression($dataset);
        $this->linearRegression->prepare();
        $this->assertTrue($this->linearRegression->train(5, 0.01) instanceof LinearRegression);
        $this->assertTrue($this->linearRegression->getEpochCollection() instanceof EpochCollection);
        $lastEpoch = $this->linearRegression->getEpochCollection()->getLastEpoch();
        $this->assertTrue($lastEpoch instanceof Epoch);
    }

    /**
     * Tests the prepare function that prepares the dataset
     * $linearRegression->prepare()
     */
    public function testPredict()
    {
        $dataset = Dataset::factory(__DIR__ . '/fixtures/LinearRegressionExample.csv', Dataset::PREDICTION);
        $this->linearRegression = new LinearRegression($dataset);
        $this->linearRegression->prepare();
        $this->linearRegression->train();
        $lastEpoch = $this->linearRegression->getEpochCollection()->getLastEpoch();
        $lastEpoch->test($dataset);
        $this->assertEquals($lastEpoch->getAlgorithm()->getScore(), 1);
        $this->assertEquals($lastEpoch->getAlgorithm()->getRmse(), 0);


        $dataset = Dataset::factory(__DIR__ . '/fixtures/LinearRegressionStochasticExample.csv', Dataset::PREDICTION);
        $this->linearRegression = new LinearRegression($dataset);
        $this->linearRegression->prepare();
        $this->linearRegression->train(20, 0.01);
        $lastEpoch = $this->linearRegression->getEpochCollection()->getLastEpoch();
        $lastEpoch->test($dataset);
        $this->assertEquals(round($lastEpoch->getAlgorithm()->getRmse(), 2), 0.70);
    }

}
