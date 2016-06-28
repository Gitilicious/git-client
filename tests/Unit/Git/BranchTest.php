<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Git;

use Gitilicious\GitClient\Git\Branch;

class BranchTest extends \PHPUnit_Framework_TestCase
{
    public function testIsActiveNotActive()
    {
        $branch = new Branch('foo');

        $this->assertFalse($branch->isActive());
    }

    public function testIsActiveWhenActive()
    {
        $branch = new Branch('* foo');

        $this->assertTrue($branch->isActive());
    }

    public function testGetName()
    {
        $branch = new Branch('foo');

        $this->assertSame('foo', $branch->getName());
    }

    public function testGetNameWhileBeingActive()
    {
        $branch = new Branch('* foo');

        $this->assertSame('foo', $branch->getName());
    }
}
