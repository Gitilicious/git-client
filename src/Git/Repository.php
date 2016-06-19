<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Git;

use Gitilicious\GitClient\Cli\Input\LongFlag;
use Gitilicious\GitClient\Cli\Input\PlainArgument;
use Gitilicious\GitClient\Cli\Input\ShortFlag;
use Gitilicious\GitClient\Client;
use Gitilicious\GitClient\FileSystem\Directory;
use Gitilicious\GitClient\FileSystem\File;

class Repository
{
    private $client;

    private $directory;

    private $name;

    public function __construct(Client $client, Directory $directory, string $name)
    {
        $this->client    = $client;
        $this->directory = $directory;
        $this->name      = $name;
    }

    public static function createBare(Client $client, Directory $directory, string $name): Repository
    {
        $repositoryDirectory = $directory->create($directory->getPath() . '/' . $name);

        $result = $client->run($repositoryDirectory, new PlainArgument('init'), new LongFlag('bare'));

        if (!$result->isSuccess()) {
            throw new Exception($result->getErrorMessage());
        }

        return new self($client, $directory, $name);
    }

    public static function create(Client $client, Directory $directory, string $name): Repository
    {
        $repositoryDirectory = $directory->create($directory->getPath() . '/' . $name);

        $result = $client->run($repositoryDirectory, new PlainArgument('init'));

        if (!$result->isSuccess()) {
            throw new Exception($result->getErrorMessage());
        }

        return new self($client, $directory, $name);
    }

    public function clone(Directory $directory, string $name): Repository
    {
        $repositoryDirectory = $directory->create($directory->getPath() . '/' . $name);

        $result = $this->client->run(
            $repositoryDirectory,
            new PlainArgument('clone'),
            new PlainArgument($this->getPath()),
            new PlainArgument($repositoryDirectory->getPath())
        );

        if (!$result->isSuccess()) {
            throw new Exception($result->getErrorMessage());
        }

        return new self($this->client, $directory, $name);
    }

    public function getPath(): string
    {
        return $this->directory->getPath() . '/' . $this->name;
    }

    public function getBranches(): Branches
    {
        $result = $this->client->run($this->getPath(), 'branch');

        if (!$result->isSuccess()) {
            throw new Exception('Cannot list the branches.');
        }

        $branches = new Branches($this->client);

        foreach ($result->getOutput() as $branch) {
            $branches->add(new Branch($branch));
        }

        return $branches;
    }

    public function add(File $file)
    {
        $this->client->run($this->directory, new PlainArgument('add'), new PlainArgument($file->getPath()));
    }

    public function commit(Author $author, string $message)
    {
        $this->client->run(
            $this->directory,
            new PlainArgument('commit'),
            new ShortFlag('m'),
            new PlainArgument($message),
            new LongFlag('author'),
            new PlainArgument($author->getFormatted())
        );
    }

    public function push()
    {
        $this->client->run($this->directory, new PlainArgument('push'));
    }
}
