<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Cli\Output;

use Gitilicious\GitClient\Cli\Output\Output;
use Gitilicious\GitClient\Cli\Output\Result;

class ResultTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSuccess()
    {
        $result = new Result(0, '', '');

        $this->assertTrue($result->isSuccess());
    }

    public function testIsSuccessWhenFailed()
    {
        $result = new Result(128, '', '');

        $this->assertFalse($result->isSuccess());
    }

    public function testGetOutputReturnsOutputObject()
    {
        $result = new Result(1, '', '');

        $this->assertInstanceOf(Output::class, $result->getOutput());
    }

    public function testGetOutputContainsLines()
    {
        $result = new Result(1, 'foo' . PHP_EOL . 'bar', '');

        $this->assertSame(2, $result->getOutput()->getNumberOfLines());
        $this->assertSame('foo', $result->getOutput()->getLine(1));
        $this->assertSame('bar', $result->getOutput()->getLine(2));
    }

    public function testGetErrorMessage()
    {
        $result = new Result(128, '', 'foo' . PHP_EOL . 'bar');

        $this->assertSame('foo', $result->getErrorMessage());
    }
}
