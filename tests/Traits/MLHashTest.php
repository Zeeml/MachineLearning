<?php

namespace Zeeml\MachineLearning\Tests\Traits;

use PHPUnit\Framework\TestCase;
use Zeeml\Algorithms\Prediction\Linear\LinearRegression;
use Zeeml\Algorithms\Prediction\Logistic\LogisticRegression;
use Zeeml\MachineLearning\Traits\MLHash;

class MLHashTest extends TestCase
{
    protected $class;

    public function setUp()
    {
        parent::setUp();

        $this->class = new class() {
            use MLHash;

            public function testHash(array $algorithms, int $nbEpochs, float $learningRate, float $split): string
            {
                return $this->hash($algorithms, $nbEpochs, $learningRate, $split);
            }
        };
    }

    /**
     * @test
     */
    public function hash_should_be_a_string()
    {
        $hash = $this->class->testHash([LinearRegression::class, LogisticRegression::class], 30, 0.004, 0.8);
        $this->assertInternalType('string', $hash);
        $this->assertTrue(strlen($hash) === 64);
    }

    /**
     * @test
     */
    public function hash_should_be_same_when_params_not_changes()
    {
        $hash1 = $this->class->testHash([LinearRegression::class, LogisticRegression::class], 30, 0.004, 0.8);
        $hash2 = $this->class->testHash([LinearRegression::class, LogisticRegression::class], 30, 0.004, 0.8);
        $this->assertEquals($hash1, $hash2);

        $hash1 = $this->class->testHash([LinearRegression::class], 0, 0, 0);
        $hash2 = $this->class->testHash([LinearRegression::class], 0, 0, 0);
        $this->assertEquals($hash1, $hash2);
    }

    /**
     * @test
     */
    public function hash_should_be_different_when_one_param_changes()
    {
        $hash1 = $this->class->testHash([LinearRegression::class, LogisticRegression::class], 30, 0.004, 0.8);
        $hash2 = $this->class->testHash([LinearRegression::class], 30, 0.004, 0.8);
        $this->assertNotEquals($hash1, $hash2);

        $hash1 = $this->class->testHash([LinearRegression::class, LogisticRegression::class], 30, 0.004, 0.8);
        $hash2 = $this->class->testHash([LinearRegression::class, LogisticRegression::class], 31, 0.004, 0.8);
        $this->assertNotEquals($hash1, $hash2);

        $hash1 = $this->class->testHash([LinearRegression::class, LogisticRegression::class], 30, 0.0041, 0.8);
        $hash2 = $this->class->testHash([LinearRegression::class, LogisticRegression::class], 30, 0.004, 0.8);
        $this->assertNotEquals($hash1, $hash2);

        $hash1 = $this->class->testHash([LinearRegression::class, LogisticRegression::class], 30, 0.004, 2);
        $hash2 = $this->class->testHash([LinearRegression::class, LogisticRegression::class], 30, 0.004, 0.8);
        $this->assertNotEquals($hash1, $hash2);

    }
}