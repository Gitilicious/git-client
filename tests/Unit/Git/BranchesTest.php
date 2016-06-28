<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Git;

use Gitilicious\GitClient\Git\Branch;
use Gitilicious\GitClient\Git\Branches;

class BranchesTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $branches = new Branches();

        $this->assertNull($branches->add(new Branch('foo')));
    }

    public function testCountWithoutBranches()
    {
        $branches = new Branches();

        $this->assertSame(0, count($branches));
    }

    public function testCountWithBranches()
    {
        $branches = new Branches();

        $branches->add(new Branch('foo'));
        $branches->add(new Branch('bar'));

        $this->assertSame(2, count($branches));
    }
}
