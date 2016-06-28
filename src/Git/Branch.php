<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Git;

class Branch
{
    private $active = false;

    private $name;

    public function __construct(string $name)
    {
        $this->active = strpos($name, '* ') === 0;
        $this->name   = ltrim($name, '* ');
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
