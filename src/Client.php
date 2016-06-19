<?php declare(strict_types=1);

namespace Gitilicious\GitClient;

use Gitilicious\GitClient\Cli\Client as CliClient;
use Gitilicious\GitClient\Cli\Input\Argument;
use Gitilicious\GitClient\Cli\Output\Result;
use Gitilicious\GitClient\FileSystem\Directory;

class Client
{
    private $cliClient;

    public function __construct(CliClient $cliClient)
    {
        $this->cliClient = $cliClient;
    }

    public function run(Directory $workingDirectory, Argument ...$arguments): Result
    {
        return $this->cliClient->run($workingDirectory, ...$arguments);
    }
}
