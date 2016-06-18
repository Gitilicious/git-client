<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Cli\Output;

use Gitilicious\GitClient\Cli\Output\Output;

class OutputTest extends \PHPUnit_Framework_TestCase
{
    public function testGetNumberOfLines()
    {
        $output = new Output('foo' . PHP_EOL . 'bar');

        $this->assertSame(2, $output->getNumberOfLines());
    }

    public function testGetLineWhenItExists()
    {
        $output = new Output('foo' . PHP_EOL . 'bar');

        $this->assertSame('foo', $output->getLine(1));
        $this->assertSame('bar', $output->getLine(2));
    }

    public function testGetLineWhenItDoesNotExist()
    {
        $output = new Output('foo' . PHP_EOL . 'bar');

        $this->assertSame('', $output->getLine(3));
    }
}
