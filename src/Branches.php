<?php declare(strict_types=1);

namespace Gitilicious\GitClient;

class Branches
{
    private $branches = [];

    public function add(Branch $branch)
    {
        $this->branches[] = $branch;
    }
}
