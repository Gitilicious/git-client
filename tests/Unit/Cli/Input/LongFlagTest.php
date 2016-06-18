<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Cli\Input;

use Gitilicious\GitClient\Cli\Input\Flag;
use Gitilicious\GitClient\Cli\Input\LongFlag;

class LongFlagTest extends \PHPUnit_Framework_TestCase
{
    public function testExtendsFlag()
    {
        $longFlag = new LongFlag('foo');

        $this->assertInstanceOf(Flag::class, $longFlag);
    }

    public function testGetArgument()
    {
        $longFlag = new LongFlag('foo');

        $this->assertSame('--foo', $longFlag->getArgument());
    }
}
