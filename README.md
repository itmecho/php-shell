# PHP Shell

[![Build Status](https://travis-ci.org/Synapse791/php-shell.svg?branch=master)](https://travis-ci.org/Synapse791/php-shell)

## Introduction

PHP Shell was written to provide an OOP wrapper around shell command execution in PHP. It enables you to receive a response object from the command execution containing the stdout, stderr and exit code of the command.

## Documentation

#### Basic usage
To create the command, pass an absolute path to the command for instantiation. You can also set where the command is executed using the `setWorkingDirectory` method.
```php
$pwd = new Neuron\Shell\Command('/bin/pwd'); // CommandNotFoundException if the command is not executable
$pwd->setWorkingDirectory('/tmp'); // DirectoryDoesNotExistException if the directory is not found
$output = $pwd->execute();
var_dump($output->getStdout()); // string(5) "/tmp"
var_dump($output->getStderr()); // string(0) ""
var_dump($output->getExitCode()); // int(0)
```

#### Adding flags
```php
$cmd = new Neuron\Shell\Command('/usr/local/bin/my_command');
$cmd->addShortFlag('v');
$cmd->addShortFlag('c', '/tmp/test.json');
$cmd->addLongFlag('version');
$cmd->addLongFlag('config', '/tmp/test.json');
```

#### Adding Arguments
```php
$echo = new Neuron\Shell\Command('/bin/echo');
$echo->addArgument('hi there!');
```

## License

PHP Shell is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)