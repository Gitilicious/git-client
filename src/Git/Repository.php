<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Git;

use Gitilicious\GitClient\FileSystem\Directory;

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
        $repositoryDirectory = $directory->getPath() . '/' . $name;

        $result = $client->run($repositoryDirectory, 'init', '--bare');

        if (!$result->isSuccess()) {
            throw new GitException($result->getErrorMessage());
        }

        return new self($client, $directory, $name);
    }

    public static function create(Client $client, Directory $directory, string $name): Repository
    {
        $repositoryDirectory = $directory->getPath() . '/' . $name;

        $result = $client->run($repositoryDirectory, 'init');

        if (!$result->isSuccess()) {
            throw new GitException($result->getErrorMessage());
        }

        return new self($client, $directory, $name);
    }

    public function clone(Directory $directory, string $name): Repository
    {
        $repositoryDirectory = $directory->getPath() . '/' . $name;

        $result = $this->client->run($repositoryDirectory, 'clone', $this->getPath(), $repositoryDirectory);

        if (!$result->isSuccess()) {
            throw new GitException($result->getErrorMessage());
        }

        return new self($this->client, $directory, $name);
    }

    public function getPath(): string
    {
        return $this->directory->getPath() . '/' . $this->name;
    }

    public function addDefaultReadMe()
    {
        file_put_contents($this->getPath() . '/README.md', "# {$this->name}\n");

        $this->client->run($this->getPath(), 'add', 'README.md');
        $this->client->run($this->getPath(), 'commit', '-m', 'Added readme');
        $this->client->run($this->getPath(), 'push');
    }

    public function getBranches(): Branches
    {
        $result = $this->client->run($this->getPath(), 'branch');

        if (!$result->isSuccess()) {
            throw new GitException('Cannot list the branches.');
        }

        $branches = new Branches($this->client);

        foreach ($result->getOutput() as $branch) {
            $branches->add(new Branch($branch));
        }

        return $branches;
    }
}
