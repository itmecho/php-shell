<?php

namespace Neuron\Shell\Exceptions;

/**
 * Class FailedToExecuteCommandException
 *
 * @package Neuron\Shell\Exceptions
 */
class FailedToExecuteCommandException extends \Exception
{
    /**
     * FailedToExecuteCommandException constructor
     *
     * @param string $cmd
     */
    public function __construct($cmd)
    {
        $message = "Failed to execute command: {$cmd}";
        parent::__construct($message);
    }
}