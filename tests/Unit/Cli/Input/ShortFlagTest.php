<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Cli\Input;

use Gitilicious\GitClient\Cli\Input\Flag;
use Gitilicious\GitClient\Cli\Input\ShortFlag;

class ShortFlagTest extends \PHPUnit_Framework_TestCase
{
    public function testExtendsFlag()
    {
        $shortFlag = new ShortFlag('foo');

        $this->assertInstanceOf(Flag::class, $shortFlag);
    }

    public function testGetArgument()
    {
        $shortFlag = new ShortFlag('foo');

        $this->assertSame('-foo', $shortFlag->getArgument());
    }
}
