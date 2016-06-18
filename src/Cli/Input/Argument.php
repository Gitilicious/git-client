<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Cli\Input;

interface Argument
{
    public function getArgument(): string;
}
