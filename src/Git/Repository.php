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

    public function __construct(Client $client, Directory $directory)
    {
        $this->client    = $client;
        $this->directory = $directory;
    }

    public static function createBare(Client $client, Directory $directory): Repository
    {
        $client->run($directory, new PlainArgument('init'), new LongFlag('bare'));

        return new self($client, $directory);
    }

    public static function create(Client $client, Directory $directory): Repository
    {
        $client->run($directory, new PlainArgument('init'));

        return new self($client, $directory);
    }

    public function clone(Directory $directory): Repository
    {
        $this->client->run(
            $directory,
            new PlainArgument('clone'),
            new PlainArgument($this->directory->getPath()),
            new PlainArgument($directory->getPath())
        );

        return new self($this->client, $directory);
    }

    public function getPath(): string
    {
        return $this->directory->getPath();
    }

    public function getActiveBranch(): Branch
    {
        $branches = $this->getBranches();

        if (!count($branches)) {
            return new Branch('* master');
        }

        foreach ($branches as $branch) {
            if ($branch->isActive()) {
                return $branch;
            }
        }
    }

    public function getBranches(): Branches
    {
        $result = $this->client->run($this->directory, new PlainArgument('branch'));

        $branches = new Branches($this->client);

        foreach ($result->getOutput() as $branch) {
            $branches->add(new Branch($branch));
        }

        return $branches;
    }

    public function createBranch(string $name): Branch
    {
        $this->client->run(
            $this->directory,
            new PlainArgument('checkout'),
            new ShortFlag('b'),
            new PlainArgument($name)
        );

        return new Branch($name);
    }

    public function checkout(string $name): Branch
    {
        $this->client->run(
            $this->directory,
            new PlainArgument('checkout'),
            new PlainArgument($name)
        );

        return new Branch('* ' . $name);
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
