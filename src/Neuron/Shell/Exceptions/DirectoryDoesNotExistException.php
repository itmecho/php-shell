<?php

namespace Neuron\Shell\Exceptions;

/**
 * Class DirectoryDoesNotExistException
 *
 * @package Neuron\Shell\Exceptions
 */
class DirectoryDoesNotExistException extends \Exception
{
    /**
     * DirectoryDoesNotExistException constructor
     *
     * @param string $directory
     */
    public function __construct($directory)
    {
        $message = "Directory '{$directory}' does not exist.";
        parent::__construct($message);
    }
}