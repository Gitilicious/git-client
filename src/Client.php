<?php declare(strict_types=1);

namespace Gitilicious\GitClient;

use Gitilicious\GitClient\Cli\Client as CliClient;
use Gitilicious\GitClient\Cli\Output\Result;

class Client
{
    private $cliClient;

    public function __construct(CliClient $cliClient)
    {
        $this->cliClient = $cliClient;
    }

    public function run(string $path, ...$arguments): Result
    {
        return $this->client($path, ...$arguments);
    }
}
