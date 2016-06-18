<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Cli\Input;

use Gitilicious\GitClient\Cli\Input\Binary;
use Gitilicious\GitClient\Cli\NotFoundException;
use Gitilicious\GitClient\Cli\PermissionDeniedException;

class BinaryTest extends \PHPUnit_Framework_TestCase
{
    public function testThrowsWhenExecutableCannotBeFound()
    {
        $nonExistentGitBinary = DATA_DIR . '/foo';

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(sprintf('The executable `%s` could not be found.', $nonExistentGitBinary));

        new Binary($nonExistentGitBinary);
    }

    public function testThrowsWhenBinaryIsNotExecutable()
    {
        $nonExecutableGitBinary = DATA_DIR . '/not-executable';

        $this->expectException(PermissionDeniedException::class);
        $this->expectExceptionMessage(sprintf('`%s` is not executable.', $nonExecutableGitBinary));

        new Binary($nonExecutableGitBinary);
    }

    public function testGetExecutable()
    {
        $binary = new Binary(GIT_BINARY);

        $this->assertSame(GIT_BINARY, $binary->getExecutable());
    }
}
