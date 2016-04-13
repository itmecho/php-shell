<?php

namespace spec\Neuron\Shell\Resources;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandOutputSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            'i am stdout text. grapes watermelon apple',
            'i am stderr text. banana mango pear',
            0
        );
    }

    function it_can_be_initialized_with_stdout_stderr_and_an_exit_code()
    {
        $this->shouldHaveType('Neuron\Shell\Resources\CommandOutput');
        $this->getStdout()->shouldReturn('i am stdout text. grapes watermelon apple');
        $this->getStderr()->shouldReturn('i am stderr text. banana mango pear');
        $this->getExitCode()->shouldReturn(0);
    }

    function it_can_determine_if_the_command_was_successful()
    {
        $this->wasSuccessful()->shouldReturn(true);
    }

    function it_can_determine_if_the_command_was_not_successful()
    {
        $this->beConstructedWith('', '', 1);
        $this->wasSuccessful()->shouldReturn(false);
    }

    function it_can_search_stdout()
    {
        $this->stdoutContains('grapes')->shouldReturn(true);
        $this->stdoutContains('banana')->shouldReturn(false);
    }

    function it_can_search_stderr()
    {
        $this->stdoutContains('grapes')->shouldReturn(true);
        $this->stdoutContains('banana')->shouldReturn(false);
    }

    function it_can_search_combined_output()
    {
        $this->combinedOutputContains('grapes')->shouldReturn(true);
        $this->combinedOutputContains('banana')->shouldReturn(true);
        $this->combinedOutputContains('cucumber')->shouldReturn(false);
    }
}
