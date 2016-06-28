<?php declare(strict_types=1);

namespace Gitilicious\GitClient;

use Gitilicious\GitClient\Cli\Client as CliClient;
use Gitilicious\GitClient\Cli\Input\Argument;
use Gitilicious\GitClient\Cli\Output\Result;
use Gitilicious\GitClient\FileSystem\Directory;
use Gitilicious\GitClient\Git\Exception;

class Client
{
    private $cliClient;

    public function __construct(CliClient $cliClient)
    {
        $this->cliClient = $cliClient;
    }

    public function run(Directory $workingDirectory, Argument ...$arguments): Result
    {
        $result = $this->cliClient->run($workingDirectory, ...$arguments);

        if (!$result->isSuccess()) {
            throw new Exception($result->getErrorMessage());
        }

        return $result;
    }
}
