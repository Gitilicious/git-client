<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Cli\Input;

use Gitilicious\GitClient\Cli\Input\Argument;
use Gitilicious\GitClient\Cli\Input\PlainArgument;

class PlainArgumentTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsCorrectInterface()
    {
        $plainArgument = new PlainArgument('foo');

        $this->assertInstanceOf(Argument::class, $plainArgument);
    }

    public function testGetArgument()
    {
        $plainArgument = new PlainArgument('foo');

        $this->assertSame('foo', $plainArgument->getArgument());
    }
}
