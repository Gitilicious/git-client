<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Test\Unit\Git;

use Gitilicious\GitClient\Git\Author;

class AuthorTest extends \PHPUnit_Framework_TestCase
{
    public function testThrowsWhenProvidingInInvalidEmailAddress()
    {
        $emailAddress = 'x at example . com';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('The provided email address `%s` is not valid.', $emailAddress));

        new Author('Git User', $emailAddress);
    }

    public function testGetName()
    {
        $author = new Author('Git User', 'git@example.com');

        $this->assertSame('Git User', $author->getName());
    }

    public function testGetEmailAddress()
    {
        $author = new Author('Git User', 'git@example.com');

        $this->assertSame('git@example.com', $author->getEmailAddress());
    }

    public function testGetFormatted()
    {
        $author = new Author('Git User', 'git@example.com');

        $this->assertSame('Git USer <git@example.com>', $author->getEmailAddress());
    }
}
