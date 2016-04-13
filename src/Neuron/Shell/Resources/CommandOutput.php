<?php

namespace Neuron\Shell\Resources;

class CommandOutput
{
    protected $stdout;

    protected $stderr;

    protected $exitCode;

    public function __construct($stdout, $stderr, $exitCode)
    {
        $this->stdout = $stdout;
        $this->stderr = $stderr;
        $this->exitCode = $exitCode;
    }

    public function getStdout()
    {
        return $this->stdout;
    }

    public function getStderr()
    {
        return $this->stderr;
    }

    public function getExitCode()
    {
        return $this->exitCode;
    }

    public function wasSuccessful()
    {
        return $this->exitCode === 0;
    }

    public function stdoutContains($searchString)
    {
        return strpos($this->stdout, $searchString) !== false;
    }

    public function stderrContains($searchString)
    {
        return strpos($this->stderr, $searchString) !== false;
    }

    public function combinedOutputContains($searchString)
    {
        return $this->stdoutContains($searchString) ? true : $this->stderrContains($searchString);
    }

}
