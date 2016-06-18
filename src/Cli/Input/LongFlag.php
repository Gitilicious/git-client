<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Cli\Input;

class LongFlag extends Flag
{
    public function getArgument(): string
    {
        return '--' . $this->flag;
    }
}
