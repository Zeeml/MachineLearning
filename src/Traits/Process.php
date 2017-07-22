<?php

namespace Zeeml\MachineLearning\Traits;

trait Process
{
    protected $status = 'created';

    protected function busy()
    {
        $this->status = 'busy';
    }

    protected function done()
    {
        $this->status = 'done';
    }

    public function isBusy(): bool
    {
        return $this->status === 'busy';
    }

    public function isDone(): bool
    {
        return $this->status === 'done';
    }
}