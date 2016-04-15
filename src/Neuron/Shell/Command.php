<?php

namespace Neuron\Shell;

use Neuron\Shell\Exceptions\CommandNotFoundException;
use Neuron\Shell\Exceptions\DirectoryDoesNotExistException;
use Neuron\Shell\Exceptions\FailedToExecuteCommandException;
use Neuron\Shell\Resources\CommandOutput;

/**
 * Class Command
 *
 * @package Neuron\Shell
 */
class Command
{
    /**
     * @var string
     */
    protected $cmd;

    /**
     * @var string
     */
    protected $cmdString;

    /**
     * @var string
     */
    protected $workingDirectory = __DIR__;

    /**
     * @var array
     */
    protected $shortFlags = [];

    /**
     * @var array
     */
    protected $longFlags = [];

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var array
     */
    protected $environmentVariables = [];

    /**
     * Command constructor
     *
     * @param $cmd
     * @throws CommandNotFoundException
     */
    public function __construct($cmd)
    {
        if( ! is_executable($cmd))
            throw new CommandNotFoundException($cmd);

        $this->cmd = $cmd;
        $this->cmdString = $cmd;
    }

    /**
     * Returns the command passed during construction
     *
     * @return string
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * Returns the current command string
     *
     * @return string
     */
    public function getCmdString()
    {
        return $this->cmdString;
    }

    /**
     * Gets the working directory for the command
     *
     * @return string
     */
    public function getWorkingDirectory()
    {
        return $this->workingDirectory;
    }

    /**
     * Sets the working directory for the command
     *
     * @param $dir
     * @return $this
     * @throws DirectoryDoesNotExistException
     */
    public function setWorkingDirectory($dir)
    {
        if(! is_dir($dir))
            throw new DirectoryDoesNotExistException($dir);

        $this->workingDirectory = $dir;

        return $this;
    }

    /**
     * Adds a short flag to the command string and stores it in the shortFlags array
     *
     * @param $key
     * @param null $value
     * @return $this
     */
    public function addShortFlag($key, $value = null)
    {
        $escaped = is_null($value) ? $value : escapeshellarg($value);

        $this->shortFlags[$key] = $escaped;

        if(is_null($escaped))
            $this->cmdString .= " -{$key}";
        else
            $this->cmdString .= " -{$key} {$escaped}";

        return $this;
    }

    /**
     * Returns an array of short flags for the command
     *
     * @return array
     */
    public function getShortFlags()
    {
        return $this->shortFlags;
    }

    /**
     * Adds a long flag to the command string and stores it in the longFlags array
     *
     * @param $key
     * @param null $value
     * @return $this
     */
    public function addLongFlag($key, $value = null)
    {
        $escaped = is_null($value) ? $value : escapeshellarg($value);

        $this->longFlags[$key] = $escaped;

        if(is_null($escaped))
            $this->cmdString .= " --{$key}";
        else
            $this->cmdString .= " --{$key} {$escaped}";

        return $this;
    }

    /**
     * Returns an array of long flags for the command
     *
     * @return array
     */
    public function getLongFlags()
    {
        return $this->longFlags;
    }

    /**
     * Adds and argument to the command string and stores it in the arguments array
     *
     * @param $arg
     * @return $this
     */
    public function addArgument($arg)
    {
        $escaped = escapeshellarg($arg);

        $this->arguments[] = $escaped;

        $this->cmdString .= " {$escaped}";

        return $this;
    }

    /**
     * Returns the arguments for the command
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Adds an environment variable to the environmentVariables array
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function addEnvironmentVariable($key, $value)
    {
        $this->environmentVariables[$key] = $value;

        return $this;
    }

    /**
     * Returns the environmentVariables array
     *
     * @return array
     */
    public function getEnvironmentVariables()
    {
        return $this->environmentVariables;
    }

    /**
     * Executes the command and returns the OutputResource containing the commands output data
     *
     * @return CommandOutput
     * @throws FailedToExecuteCommandException
     */
    public function execute()
    {
        $descriptors = [
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w'),
        ];

        $process = proc_open($this->cmdString, $descriptors, $pipes, $this->workingDirectory, $this->environmentVariables);

        if( is_resource($process) ) {

            fclose($pipes[0]);

            $stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $exitCode = proc_close($process);

            return new CommandOutput($stdout, $stderr, $exitCode);

        } else {

            throw new FailedToExecuteCommandException($this->cmdString);

        }

    }

}
