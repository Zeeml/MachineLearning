<?php

use PHPUnit\Framework\TestCase;
use Zeeml\Algorithms\Epoch;
use Zeeml\Dataset\Dataset;
use Zeeml\Algorithms\Traits\SimpleLinearRegressionAlgorithm;
use Zeeml\Algorithms\EpochCollection;

/**
 * Class LinearRegressionTest
 */
class EpochCollectionTest extends TestCase
{
    protected $epoch;
    protected $simpleLinearRegression;

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
        parent::tearDown();
    }

    /**
     * Tests the prepare function that prepares the dataset
     * $simpleLinearRegression->prepare()
     */
    public function testCollectionFactory()
    {
        $dataset = Dataset::factory(__DIR__ . '/fixtures/LinearRegressionExample.csv', Dataset::PREDICTION);
        $dataset->prepare();
        $epochs = EpochCollection::collectionFactory(new SimpleLinearRegressionAlgorithm(), 10);
        $this->assertTrue($epochs instanceof EpochCollection);

        $counter = 0;
        foreach ($epochs as $epoch) {
            $this->assertTrue($epoch instanceof Epoch);
            $counter++;
        }

        $this->assertTrue($counter === 10);
    }
}
