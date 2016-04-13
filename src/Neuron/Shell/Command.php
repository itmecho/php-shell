<?php

namespace Neuron\Shell;

use Neuron\Shell\Exceptions\CommandNotFoundException;
use Neuron\Shell\Exceptions\DirectoryDoesNotExistException;
use Neuron\Shell\Exceptions\FailedToExecuteCommandException;
use Neuron\Shell\Resources\CommandOutput;

class Command
{
    protected $cmd;

    protected $cmdString;

    protected $workingDirectory = '/tmp';

    protected $shortFlags = [];

    protected $longFlags = [];

    protected $arguments = [];

    protected $environmentVariables = [];

    public function __construct($cmd)
    {
        if( ! is_executable($cmd))
            throw new CommandNotFoundException($cmd);

        $this->cmd = $cmd;
        $this->cmdString = $cmd;
    }

    public function getCmd()
    {
        return $this->cmd;
    }

    public function getCmdString()
    {
        return $this->cmdString;
    }

    public function getWorkingDirectory()
    {
        return $this->workingDirectory;
    }

    public function setWorkingDirectory($dir)
    {
        if(! is_dir($dir))
            throw new DirectoryDoesNotExistException($dir);

        $this->workingDirectory = $dir;

        return $this;
    }

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

    public function getShortFlags()
    {
        return $this->shortFlags;
    }

    public function getLongFlags()
    {
        return $this->longFlags;
    }

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

    public function getArguments()
    {
        return $this->arguments;
    }

    public function addArgument($arg)
    {
        $escaped = escapeshellarg($arg);

        $this->arguments[] = $escaped;

        $this->cmdString .= " {$escaped}";

        return $this;
    }

    public function addEnvironmentVariable($key, $value)
    {
        $this->environmentVariables[$key] = $value;

        return $this;
    }

    public function getEnvironmentVariables()
    {
        return $this->environmentVariables;
    }

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
