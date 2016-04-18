<?php

namespace spec\Neuron\Shell;

use Neuron\Shell\Command;
use Neuron\Shell\Exceptions\CommandNotFoundException;
use Neuron\Shell\Exceptions\DirectoryDoesNotExistException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('/bin/true');
    }

    function it_can_be_initialized_with_a_command()
    {
        $this->shouldHaveType('Neuron\Shell\Command');
        $this->getCmd()->shouldReturn('/bin/true');
        $this->getCmdString()->shouldReturn('/bin/true');
    }

    function it_throws_an_exception_when_a_command_is_not_found()
    {
        $this->beConstructedWith('/not_a_command');
        $this->shouldThrow(new CommandNotFoundException('/not_a_command'))->duringInstantiation();
    }

    function it_can_set_a_working_directory_for_the_command()
    {
        $defaultDir = str_replace('spec', 'src', __DIR__);
        $realDir = __DIR__;

        $this->getWorkingDirectory()->shouldReturn($defaultDir);
        $this->setWorkingDirectory($realDir)->shouldReturn($this);
        $this->getWorkingDirectory()->shouldReturn($realDir);
    }

    function it_throws_an_exception_whilst_setting_working_directory_if_directory_does_not_exist()
    {
        $this->shouldThrow(new DirectoryDoesNotExistException('/i/dont/exist'))->duringSetWorkingDirectory('/i/dont/exist');
    }

    function it_can_add_a_short_flag_with_no_value()
    {
        $this->getShortFlags()->shouldReturn([]);
        $this->addShortFlag('a')->shouldReturn($this);
        $this->getShortFlags()->shouldReturn(['a' => null]);
        $this->getCmdString()->shouldReturn('/bin/true -a');
    }

    function it_can_add_a_short_flag_with_a_value()
    {
        $this->getShortFlags()->shouldReturn([]);
        $this->addShortFlag('a', 'test')->shouldReturn($this);
        $this->getShortFlags()->shouldReturn(['a' => '\'test\'']);
        $this->getCmdString()->shouldReturn('/bin/true -a \'test\'');
    }

    function it_can_add_a_long_flag_with_no_value()
    {
        $this->getLongFlags()->shouldReturn([]);
        $this->addLongFlag('test')->shouldReturn($this);
        $this->getLongFlags()->shouldReturn(['test' => null]);
        $this->getCmdString()->shouldReturn('/bin/true --test');
    }

    function it_can_add_a_long_flag_with_a_value()
    {
        $this->getLongFlags()->shouldReturn([]);
        $this->addLongFlag('test', 'value')->shouldReturn($this);
        $this->getLongFlags()->shouldReturn(['test' => '\'value\'']);
        $this->getCmdString()->shouldReturn('/bin/true --test \'value\'');
    }

    function it_can_add_an_argument()
    {
        $this->getArguments()->shouldReturn([]);
        $this->addArgument('test')->shouldReturn($this);
        $this->getArguments()->shouldReturn(['\'test\'']);
        $this->getCmdString('/bin/true \'test\'');
    }

    function it_builds_the_command_string_sequentially()
    {
        $this->getCmdString()->shouldReturn('/bin/true');
        $this->addShortFlag('v');
        $this->addArgument('test');
        $this->addLongFlag('config', '/test/config.json');
        $this->getCmdString()->shouldReturn('/bin/true -v \'test\' --config \'/test/config.json\'');
    }

    function it_can_add_environment_variables_for_the_command()
    {
        $this->addEnvironmentVariable('key', 'value')->shouldReturn($this);
        $this->addEnvironmentVariable('grapes', 'sweet')->shouldReturn($this);
        $this->getEnvironmentVariables()->shouldReturn([
            'key' => 'value',
            'grapes' => 'sweet',
        ]);
    }

    function it_can_pipe_commands_through_other_commands()
    {
        $cmd2 = new Command('/bin/false');
        $this->getPipes()->shouldReturn([]);
        $this->pipe($cmd2)->shouldReturn($this);
        $this->getPipes()->shouldReturn(['/bin/false']);
        $this->getCmdString()->shouldReturn('/bin/true | /bin/false');
    }

    function it_can_execute_a_command()
    {
        $this->execute()->shouldReturnAnInstanceOf('Neuron\Shell\Resources\CommandOutput');
    }

}
