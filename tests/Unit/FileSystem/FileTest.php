<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\FileSystem;

use Gitilicious\GitClient\FileSystem\File;
use Gitilicious\GitClient\FileSystem\ExistsException;
use Gitilicious\GitClient\FileSystem\NotFoundException;
use Gitilicious\GitClient\FileSystem\PermissionDeniedException;

class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testThrowsOnNonExistentFile()
    {
        $nonExistentFile = DATA_DIR . '/nonexistentfile';

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('The file `%s` does not exist.', $nonExistentFile));

        new File($nonExistentFile);
    }

    public function testGetPath()
    {
        $existentFile = DATA_DIR . '/existing-file.php';

        $file = new File($existentFile);

        $this->assertSame($existentFile, $file->getPath());
    }

    public function testCreateThrowsWhenFileAlreadyExists()
    {
        $existentFile = DATA_DIR . '/existing-file.php';

        $this->expectException(ExistsException::class);
        $this->expectExceptionMessage(sprintf('The file `%s` already exists.', $existentFile));

        File::create($existentFile, 'foo');
    }

    public function testCreateReturnsFileObject()
    {
        $newFile = DATA_DIR . '/new-file';

        $file = File::create($newFile, 'foo');

        $this->assertTrue(is_file($newFile));
        $this->assertSame($newFile, $file->getPath());
        $this->assertSame('foo', file_get_contents($newFile));

        unlink($newFile);
    }

    public function testCreateThrowsWhenTheFileCouldNotBeCreated()
    {
        $newFile = DATA_DIR . '/nonexistentdirectory/new-file';

        $this->expectException(PermissionDeniedException::class);

        File::create($newFile, 'foo');
    }
}
