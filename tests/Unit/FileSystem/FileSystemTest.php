<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\FileSystem;

use Gitilicious\GitClient\FileSystem\Directory;
use Gitilicious\GitClient\FileSystem\FileSystem;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    public function testDirectoryExistsWhenItExists()
    {
        $fileSystem = new FileSystem();

        $this->assertTrue($fileSystem->directoryExists(DATA_DIR . '/existing-directory'));
    }

    public function testDirectoryExistsWhenItDoesNotExists()
    {
        $fileSystem = new FileSystem();

        $this->assertFalse($fileSystem->directoryExists(DATA_DIR . '/nonexistentdirectory'));
    }

    public function testCreateDirectory()
    {
        $newDirectory = DATA_DIR . '/new-directory';

        $fileSystem = new FileSystem();

        $directory = $fileSystem->createDirectory($newDirectory);

        $this->assertInstanceOf(Directory::class, $directory);
        $this->assertTrue(is_dir($newDirectory));
        $this->assertSame($newDirectory, $directory->getPath());

        rmdir($newDirectory);
    }

    public function testCreateDirectoryWithCustomPermissions()
    {
        if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
            $this->markTestSkipped('Windows does not support POSIX permissions.');

            return;
        }

        $newDirectory = DATA_DIR . '/new-directory';

        $fileSystem = new FileSystem();

        $fileSystem->createDirectory($newDirectory, 0700);

        $this->assertSame('0700', substr(sprintf('%o', fileperms($newDirectory)), -4));

        rmdir($newDirectory);
    }
}
