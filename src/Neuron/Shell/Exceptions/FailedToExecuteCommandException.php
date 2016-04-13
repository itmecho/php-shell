<?php

namespace Neuron\Shell\Exceptions;

class FailedToExecuteCommandException extends \Exception
{
    public function __construct($cmd)
    {
        $message = "Failed to execute command: {$cmd}";
        parent::__construct($message);
    }
}