<?php

namespace Neuron\Shell;

use Neuron\Shell\Exceptions\DirectoryDoesNotExistException;

class Command
{
    protected $cmd;

    protected $cmdString;

    protected $workingDirectory = '/tmp';

    protected $shortFlags = [];

    protected $longFlags = [];

    protected $arguments = [];

    public function __construct($cmd)
    {
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

}
