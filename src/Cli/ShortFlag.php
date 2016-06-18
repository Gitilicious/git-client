<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Cli;

class ShortFlag extends Flag
{
    public function getArgument(): string
    {
        return '-' . $this->flag;
    }
}
