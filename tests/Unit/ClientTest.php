<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit;

use Gitilicious\GitClient\Cli\Input\Argument;
use Gitilicious\GitClient\Cli\Input\ShortFlag;
use Gitilicious\GitClient\Cli\Output\Result;
use Gitilicious\GitClient\Client;
use \Gitilicious\GitClient\Cli\Client as CliClient;
use Gitilicious\GitClient\FileSystem\Directory;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testRunPassesArguments()
    {
        $client = new Client(new class extends CliClient {
            public function __construct() {}

            public function run(Directory $workingDirectory, Argument ...$arguments): Result
            {
                \PHPUnit_Framework_Assert::assertSame(1, count($arguments));
                \PHPUnit_Framework_Assert::assertSame('-foo', $arguments[0]->getArgument());

                return new Result(0, '', '');
            }
        });

        $client->run(new Directory(DATA_DIR), new ShortFlag('foo'));
    }

    public function testRunReturnsResult()
    {
        $client = new Client(new class extends CliClient {
            public function __construct() {}

            public function run(Directory $workingDirectory, Argument ...$arguments): Result
            {
                return new Result(0, '', '');
            }
        });

        $result = $client->run(new Directory(DATA_DIR), new ShortFlag('foo'));

        $this->assertInstanceOf(Result::class, $result);
    }
}
