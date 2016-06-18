<?php declare(strict_types=1);

namespace Gitilicious\GitClient;

use Gitilicious\GitClient\Cli\Client as CliClient;
use Gitilicious\GitClient\Cli\Input\Argument;
use Gitilicious\GitClient\Cli\Input\PlainArgument;
use Gitilicious\GitClient\Cli\Output\Result;

class Client
{
    private $cliClient;

    public function __construct(CliClient $cliClient)
    {
        $this->cliClient = $cliClient;
    }

    public function run(string $path, Argument ...$arguments): Result
    {
        return $this->cliClient->run(array_merge([new PlainArgument($path)], $arguments));
    }
}
