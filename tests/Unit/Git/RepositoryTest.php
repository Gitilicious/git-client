<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Git;

use Gitilicious\GitClient\Cli\Input\Argument;
use Gitilicious\GitClient\Cli\Input\LongFlag;
use Gitilicious\GitClient\Cli\Input\PlainArgument;
use Gitilicious\GitClient\Cli\Output\Result;
use Gitilicious\GitClient\Client;
use Gitilicious\GitClient\FileSystem\Directory;
use Gitilicious\GitClient\Git\Exception;
use Gitilicious\GitClient\Git\Repository;

class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $failedResultClient;

    private $successResultClient;

    public function setUp()
    {
        $this->failedResultClient = new class extends Client {
            public function __construct() {}

            public function run(Directory $workingDirectory, Argument ...$arguments): Result
            {
                return new Result(128, '', 'foo');
            }
        };

        $this->successResultClient = new class extends Client {
            public function __construct() {}

            public function run(Directory $workingDirectory, Argument ...$arguments): Result
            {
                return new Result(0, '', '');
            }
        };
    }

    public function tearDown()
    {
        @rmdir(DATA_DIR . '/foo');
        @rmdir(DATA_DIR . '/foo2');
    }

    public function testCreateBareThrowsWhenCommandFailed()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('foo');

        Repository::createBare($this->failedResultClient, new Directory(DATA_DIR), 'foo');
    }

    public function testCreateBareReturnsRepository()
    {
        $repository = Repository::createBare($this->successResultClient, new Directory(DATA_DIR), 'foo');

        $this->assertInstanceOf(Repository::class, $repository);
    }

    public function testCreateBareCreatesDirectory()
    {
        Repository::createBare($this->successResultClient, new Directory(DATA_DIR), 'foo');

        $this->assertTrue(is_dir(DATA_DIR . '/foo'));
    }

    public function testCreateBareCorrectCommand()
    {
        $client = new class extends Client {
            public function __construct() {}

            public function run(Directory $workingDirectory, Argument ...$arguments): Result
            {
                \PHPUnit_Framework_Assert::assertSame(2, count($arguments));
                \PHPUnit_Framework_Assert::assertInstanceOf(PlainArgument::class, $arguments[0]);
                \PHPUnit_Framework_Assert::assertSame('init', $arguments[0]->getArgument());
                \PHPUnit_Framework_Assert::assertInstanceOf(LongFlag::class, $arguments[1]);
                \PHPUnit_Framework_Assert::assertSame('--bare', $arguments[1]->getArgument());

                return new Result(0, '', '');
            }
        };

        Repository::createBare($client, new Directory(DATA_DIR), 'foo');
    }

    public function testCreateThrowsWhenCommandFailed()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('foo');

        Repository::create($this->failedResultClient, new Directory(DATA_DIR), 'foo');
    }

    public function testCreateReturnsRepository()
    {
        $repository = Repository::create($this->successResultClient, new Directory(DATA_DIR), 'foo');

        $this->assertInstanceOf(Repository::class, $repository);
    }

    public function testCreateCreatesDirectory()
    {
        Repository::create($this->successResultClient, new Directory(DATA_DIR), 'foo');

        $this->assertTrue(is_dir(DATA_DIR . '/foo'));
    }

    public function testCreateCorrectCommand()
    {
        $client = new class extends Client {
            public function __construct() {}

            public function run(Directory $workingDirectory, Argument ...$arguments): Result
            {
                \PHPUnit_Framework_Assert::assertSame(1, count($arguments));
                \PHPUnit_Framework_Assert::assertInstanceOf(PlainArgument::class, $arguments[0]);
                \PHPUnit_Framework_Assert::assertSame('init', $arguments[0]->getArgument());

                return new Result(0, '', '');
            }
        };

        Repository::create($client, new Directory(DATA_DIR), 'foo');
    }

    public function testCloneThrowsWhenCommandFails()
    {
        $client = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $client
            ->expects($this->exactly(2))
            ->method('run')
            ->will($this->onConsecutiveCalls(
                new Result(0, '', ''),
                new Result(128, '', 'foo')
            ))
        ;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('foo');

        $repository = Repository::create($client, new Directory(DATA_DIR), 'foo');
        $repository->clone(new Directory(DATA_DIR), 'foo2');
    }

    public function testCloneReturnsRepository()
    {
        $repository = Repository::create($this->successResultClient, new Directory(DATA_DIR), 'foo');

        $this->assertInstanceOf(Repository::class, $repository->clone(new Directory(DATA_DIR), 'foo2'));
    }

    public function testCloneCreatesRepository()
    {
        $repository = Repository::create($this->successResultClient, new Directory(DATA_DIR), 'foo');

        $repository->clone(new Directory(DATA_DIR), 'foo2');

        $this->assertTrue(is_dir(DATA_DIR . '/foo'));
        $this->assertTrue(is_dir(DATA_DIR . '/foo2'));
    }

    public function testGetPath()
    {
        $repository = Repository::create($this->successResultClient, new Directory(DATA_DIR), 'foo');

        $this->assertSame(DATA_DIR . '/foo', $repository->getPath());
    }
}
