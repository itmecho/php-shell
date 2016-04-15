<?php

namespace Neuron\Shell\Exceptions;

/**
 * Class CommandNotFoundException
 *
 * @package Neuron\Shell\Exceptions
 */
class CommandNotFoundException extends \Exception
{
    /**
     * CommandNotFoundException constructor
     *
     * @param string $cmd
     */
    public function __construct($cmd)
    {
        $message = "Command not found: '{$cmd}'. Make sure to pass the absolute path to the binary.";
        parent::__construct($message);
    }
}