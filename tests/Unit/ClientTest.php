<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\FileSystem;

use Gitilicious\GitClient\Cli\Input\ShortFlag;
use Gitilicious\GitClient\Cli\Output\Result;
use Gitilicious\GitClient\Client;
use \Gitilicious\GitClient\Cli\Client as CliClient;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testRunPassesArguments()
    {
        $client = new Client(new class extends CliClient {
            public function __construct() {}

            public function run(array $arguments = []): Result
            {
                \PHPUnit_Framework_Assert::assertSame(2, count($arguments));
                \PHPUnit_Framework_Assert::assertSame('-foo', $arguments[1]->getArgument());

                return new Result(0, '', '');
            }
        });

        $client->run(DATA_DIR, new ShortFlag('foo'));
    }

    public function testRunReturnsResult()
    {
        $client = new Client(new class extends CliClient {
            public function __construct() {}

            public function run(array $arguments = []): Result
            {
                return new Result(0, '', '');
            }
        });

        $result = $client->run(DATA_DIR, new ShortFlag('foo'));

        $this->assertInstanceOf(Result::class, $result);
    }
}
