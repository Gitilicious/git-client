<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\FileSystem;

use Gitilicious\GitClient\FileSystem\Directory;
use Gitilicious\GitClient\FileSystem\ExistsException;
use Gitilicious\GitClient\FileSystem\NotFoundException;
use Gitilicious\GitClient\FileSystem\PermissionDeniedException;

class DirectoryTest extends \PHPUnit_Framework_TestCase
{
    public function testThrowsOnNonExistentDirectory()
    {
        $nonExistentDirectory = DATA_DIR . '/nonexistentdirectory';

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('The directory `%s` does not exist.', $nonExistentDirectory));

        new Directory($nonExistentDirectory);
    }

    public function testGetPath()
    {
        $existentDirectory = DATA_DIR . '/existing-directory';

        $directory = new Directory($existentDirectory);

        $this->assertSame($existentDirectory, $directory->getPath());
    }

    public function testCreateThrowsWhenDirectoryAlreadyExists()
    {
        $existentDirectory = DATA_DIR . '/existing-directory';

        $this->expectException(ExistsException::class);
        $this->expectExceptionMessage(sprintf('The directory `%s` already exists.', $existentDirectory));

        Directory::create($existentDirectory);
    }

    public function testCreateReturnsDirectoryObject()
    {
        $newDirectory = DATA_DIR . '/new-directory';

        $directory = Directory::create($newDirectory);

        $this->assertTrue(is_dir($newDirectory));
        $this->assertSame($newDirectory, $directory->getPath());

        rmdir($newDirectory);
    }

    public function testCreateSetsCorrectPermissions()
    {
        if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
            $this->markTestSkipped('Windows does not support POSIX permissions.');

            return;
        }

        $newDirectory = DATA_DIR . '/new-directory';

        Directory::create($newDirectory, 0700);

        $this->assertSame('0700', substr(sprintf('%o', fileperms($newDirectory)), -4));

        rmdir($newDirectory);
    }

    public function testCreateThrowsWhenTheDirectoryCouldNotBeCreated()
    {
        if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
            $this->markTestSkipped('Windows does not support POSIX permissions.');

            return;
        }

        $newDirectory = DATA_DIR . '/new-directory';

        Directory::create($newDirectory, 0444);

        $this->expectException(PermissionDeniedException::class);

        Directory::create($newDirectory . '/foobar');

        chmod($newDirectory, 0770);
        rmdir($newDirectory . '/foobar');
        rmdir($newDirectory);
    }
}
