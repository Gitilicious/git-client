<?php declare(strict_types=1);

namespace Gitilicious\GitClient;

class Branch
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = trim($name, '* ');
    }

    public function getName(): string
    {
        $this->name;
    }
}
