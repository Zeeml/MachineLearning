<?php

use PHPUnit\Framework\TestCase;
use Zeeml\Algorithms\Epoch;
use Zeeml\Dataset\Dataset;
use Zeeml\Algorithms\Prediction\LinearRegression;
use Zeeml\Algorithms\EpochCollection;

/**
 * Class LinearRegressionTest
 */
class EpochTest extends TestCase
{
    protected $epoch;

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
}
