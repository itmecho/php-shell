<?php

namespace Neuron\Shell\Exceptions;

class CommandNotFoundException extends \Exception
{
    public function __construct($cmd)
    {
        $message = "Command not found: {$cmd}";
        parent::__construct($message);
    }
}