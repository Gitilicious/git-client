<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Cli\Input;

use Gitilicious\GitClient\Cli\Input\Flag;
use Gitilicious\GitClient\Cli\Input\Argument;

class FlagTest extends \PHPUnit_Framework_TestCase
{
    private $flag;

    public function setUp()
    {
        $this->flag = new class('foo') extends Flag {
            public function getArgument(): string {}
        };
    }

    public function testImplementsCorrectInterface()
    {
        $this->assertInstanceOf(Argument::class, $this->flag);
    }
}
