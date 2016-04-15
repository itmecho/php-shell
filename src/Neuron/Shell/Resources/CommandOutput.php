<?php

namespace Neuron\Shell\Resources;

/**
 * Class CommandOutput
 *
 * @package Neuron\Shell\Resources
 */
class CommandOutput
{
    /**
     * @var string
     */
    protected $stdout;

    /**
     * @var string
     */
    protected $stderr;

    /**
     * @var int
     */
    protected $exitCode;

    /**
     * CommandOutput constructor
     *
     * @param $stdout
     * @param $stderr
     * @param $exitCode
     */
    public function __construct($stdout, $stderr, $exitCode)
    {
        $this->stdout = $stdout;
        $this->stderr = $stderr;
        $this->exitCode = $exitCode;
    }

    /**
     * Returns the stdout
     *
     * @return string
     */
    public function getStdout()
    {
        return $this->stdout;
    }

    /**
     * Returns the stderr
     *
     * @return string
     */
    public function getStderr()
    {
        return $this->stderr;
    }

    /**
     * Returns the exit code
     *
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * Checks if the command was successful based on the exit code
     *
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->exitCode === 0;
    }

    /**
     * Searches for a string in stdout
     *
     * @param $searchString
     * @return bool
     */
    public function stdoutContains($searchString)
    {
        return strpos($this->stdout, $searchString) !== false;
    }

    /**
     * Searches for a string in stderr
     *
     * @param $searchString
     * @return bool
     */
    public function stderrContains($searchString)
    {
        return strpos($this->stderr, $searchString) !== false;
    }

    /**
     * Searches for a string in both stdout and stderr
     *
     * @param $searchString
     * @return bool
     */
    public function combinedOutputContains($searchString)
    {
        return $this->stdoutContains($searchString) || $this->stderrContains($searchString);
    }

}
