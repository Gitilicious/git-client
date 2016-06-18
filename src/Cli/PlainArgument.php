<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Cli;

class PlainArgument implements Argument
{
    protected $argument;

    public function __construct(string $argument)
    {
        $this->argument = $argument;
    }

    public function getArgument(): string
    {
        return $this->argument;
    }
}
