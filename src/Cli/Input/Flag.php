<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Cli\Input;

abstract class Flag implements Argument
{
    protected $flag;

    public function __construct(string $flag)
    {
        $this->flag = $flag;
    }

    abstract public function getArgument(): string;
}
