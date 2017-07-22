<?php

namespace Zeeml\MachineLearning\Tests\Traits;

use PHPUnit\Framework\TestCase;
use Zeeml\MachineLearning\Traits\Process;

class ProcessTest extends TestCase
{
    protected $class;

    public function setUp()
    {
        parent::setUp();
        $this->class = new class {
            use Process;

            public function getStatus()
            {
                return $this->status;
            }

            public function testBusy()
            {
                $this->busy();
            }

            public function testDone()
            {
                $this->done();
            }
        };
    }

    /**
     * @test
     */
    public function status_at_first_must_be_creates()
    {
        $this->assertEquals('created', $this->class->getStatus());
    }

    /**
     * @test
     */
    public function should_not_be_busy_upon_creation()
    {
        $this->assertFalse($this->class->isBusy());
    }

    /**
     * @test
     */
    public function should_not_be_done_upon_creation()
    {
        $this->assertFalse($this->class->isDone());
    }

    /**
     * @test
     */
    public function should_not_be_created_after_busy()
    {
        $this->class->testBusy();
        $this->assertNotEquals($this->class->getStatus(), 'created');
    }

    /**
     * @test
     */
    public function should_not_be_created_after_done()
    {
        $this->class->testDone();
        $this->assertNotEquals($this->class->getStatus(), 'created');
    }

    /**
     * @test
     */
    public function should_be_only_busy_after_calling_busy()
    {
        $this->class->testBusy();
        $this->assertEquals($this->class->getStatus(), 'busy');
        $this->assertTrue($this->class->isBusy());
        $this->assertFalse($this->class->isDone());
    }

    /**
     * @test
     */
    public function should_be_only_done_after_calling_busy()
    {
        $this->class->testDone();
        $this->assertEquals($this->class->getStatus(), 'done');
        $this->assertTrue($this->class->isDone());
        $this->assertFalse($this->class->isBusy());
    }

    /**
     * @test
     */
    public function last_action_wins()
    {
        $this->assertEquals('created', $this->class->getStatus());

        $this->class->testBusy();
        $this->assertEquals($this->class->getStatus(), 'busy');
        $this->assertTrue($this->class->isBusy());
        $this->assertFalse($this->class->isDone());

        $this->class->testDone();
        $this->assertEquals($this->class->getStatus(), 'done');
        $this->assertTrue($this->class->isDone());
        $this->assertFalse($this->class->isBusy());

        $this->class->testBusy();
        $this->assertEquals($this->class->getStatus(), 'busy');
        $this->assertTrue($this->class->isBusy());
        $this->assertFalse($this->class->isDone());


    }
}