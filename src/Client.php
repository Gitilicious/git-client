<?php declare(strict_types=1);

namespace Gitilicious\GitClient;

class Client
{
    private $gitBinary;

    public function __construct(string $gitBinary)
    {
        $this->gitBinary = $gitBinary;
    }

    public function run(string $path, ...$arguments): CommandResult
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

        return new CommandResult(proc_close($process), $stdOut, $stdErr);
    }

    private function buildCommand(array $arguments): string
    {
        $command = [];

        $command[] = $this->gitBinary;

        foreach ($arguments as $argument) {
            $command[] = escapeshellarg($argument);
        }

        return implode(' ', $command);
    }
}
