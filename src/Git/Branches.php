<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Git;

class Branches implements \Countable, \Iterator
{
    private $branches = [];

    public function add(Branch $branch)
    {
        $this->branches[] = $branch;
    }

    public function count(): int
    {
        return count($this->branches);
    }

    public function rewind()
    {
        reset($this->branches);
    }

    public function current(): Branch
    {
        return current($this->branches);
    }

    public function key(): int
    {
        return key($this->branches);
    }

    public function next(): Branch
    {
        return next($this->branches);
    }

    public function valid(): bool
    {
        $key = key($this->branches);

        return ($key !== null && $key !== false);
    }
}
