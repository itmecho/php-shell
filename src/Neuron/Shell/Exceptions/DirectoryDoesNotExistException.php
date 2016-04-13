<?php

namespace Neuron\Shell\Exceptions;

class DirectoryDoesNotExistException extends \Exception
{
    public function __construct($directory)
    {
        $message = "Directory '{$directory}' does not exist.";
        parent::__construct($message);
    }
}