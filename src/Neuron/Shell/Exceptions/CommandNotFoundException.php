<?php

namespace Neuron\Shell\Exceptions;

class CommandNotFoundException extends \Exception
{
    public function __construct($cmd)
    {
        $message = "Command not found: '{$cmd}'. Make sure to pass the absolute path to the binary.";
        parent::__construct($message);
    }
}