<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Cli;

use Gitilicious\GitClient\Cli\Input\Binary;
use Gitilicious\GitClient\Cli\Output\Result;

class Client
{
    private $binary;

    public function __construct(Binary $binary)
    {
        $this->binary = $binary;
    }

    public function run(string $path, ...$arguments): Result
    {
        $descriptorSpec = [
           ['pipe', 'r'],
           ['pipe', 'w'],
           ['pipe', 'w'],
        ];

        $process = proc_open($this->buildCommand($arguments), $descriptorSpec, $pipes, $path);

        $stdOut = stream_get_contents($pipes[1]);
        $stdErr = stream_get_contents($pipes[2]);

        foreach ($pipes as $pipe) {
            fclose($pipe);
        }

        return new Result(proc_close($process), $stdOut, $stdErr);
    }

    private function buildCommand(array $arguments): string
    {
        $fullCommand = [];

        $fullCommand[] = $this->binary;

        foreach ($arguments as $argument) {
            $fullCommand[] = escapeshellarg($argument);
        }

        return implode(' ', $fullCommand);
    }
}
