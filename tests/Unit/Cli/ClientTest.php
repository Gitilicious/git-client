<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Cli;

use Gitilicious\GitClient\Cli\Client;
use Gitilicious\GitClient\Cli\Input\Binary;
use Gitilicious\GitClient\Cli\Input\PlainArgument;
use Gitilicious\GitClient\FileSystem\Directory;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testRunReturnsSuccessResult()
    {
        $binary = new class extends Binary {
            public function __construct() {}

            public function getExecutable(): string
            {
                return 'cd';
            }
        };

        $client = new Client($binary);

        $result = $client->run(new Directory(DATA_DIR), new PlainArgument(DATA_DIR . '/existing-directory'));

        $this->assertTrue($result->isSuccess());
    }

    public function testRunReturnsFailedResult()
    {
        $binary = new class extends Binary {
            public function __construct() {}

            public function getExecutable(): string
            {
                return 'invalidcommand';
            }
        };

        $client = new Client($binary);

        $result = $client->run(new Directory(DATA_DIR), new PlainArgument(DATA_DIR . '/existing-directory'));

        $this->assertFalse($result->isSuccess());
    }
}
