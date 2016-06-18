<?php declare(strict_types=1);

namespace Gitilicious\GitClient;

class Repository
{
    private $client;

    private $path;

    private $owner;

    private $name;

    public function __construct(Client $client, string $path, string $owner, string $name)
    {
        $this->client = $client;
        $this->path   = $path;
        $this->owner  = $owner;
        $this->name   = $name;
    }

    public static function create(Client $client, string $directory, string $owner, string $name): Repository
    {
        $repositoryDirectory = $directory . '/' . $owner . '/' . $name . '.git';

        if (!is_dir($repositoryDirectory)) {
            @mkdir($repositoryDirectory, 0770, true);
        }

        if (!is_dir($repositoryDirectory)) {
            throw new FileSystemException(error_get_last()['message'], error_get_last()['type']);
        }

        $result = $client->run($repositoryDirectory, 'init', '--bare');

        if (!$result->isSuccess()) {
            throw new GitException($result->getErrorMessage());
        }

        return new self($client, $directory, $owner, $name . '.git');
    }

    public static function clone(
        Client $client,
        string $directory,
        string $owner,
        string $name,
        string $repository
    ): Repository
    {
        $repositoryDirectory = $directory . '/' . $owner . '/' . $name;

        if (!is_dir($repositoryDirectory)) {
            @mkdir($repositoryDirectory, 0770, true);
        }

        if (!is_dir($repositoryDirectory)) {
            throw new FileSystemException(error_get_last()['message'], error_get_last()['type']);
        }

        $result = $client->run($repositoryDirectory, 'clone', $repository, $repositoryDirectory);

        if (!$result->isSuccess()) {
            throw new GitException($result->getErrorMessage());
        }

        return new self($client, $directory, $owner, $name);
    }

    public function getPath(): string
    {
        return sprintf('%s/%s/%s', $this->path, $this->owner, $this->name);
    }

    public function addDefaultReadMe()
    {
        $repositoryDirectory = $this->path . '/' . $this->owner . '/' . $this->name;

        file_put_contents($repositoryDirectory . '/README.md', "# {$this->name}\n");

        $this->client->run($repositoryDirectory, 'add', 'README.md');
        $this->client->run($repositoryDirectory, 'commit', '-m', 'Added readme');
        $this->client->run($repositoryDirectory, 'push');
    }

    public function getBranches(): Branches
    {
        $result = $this->client->run($this->path . '/' . $this->owner . '/' . $this->name, 'branch');

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
