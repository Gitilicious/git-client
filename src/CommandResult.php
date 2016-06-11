<?php declare(strict_types=1);

namespace Gitilicious\GitClient;

class CommandResult
{
    private $exitCode;

    private $stdOut = [];

    private $stdErr = [];

    public function __construct(int $exitCode, string $stdOut, string $stdErr)
    {
        $this->exitCode = $exitCode;
        $this->stdOut   = new CommandOutput($stdOut);
        $this->stdErr   = new CommandOutput($stdErr);
    }

    public function isSuccess(): bool
    {
        return $this->exitCode === 0;
    }

    public function getOutput(int $lineNumber): string
    {
        return $this->stdOut->getLine($lineNumber);
    }

    public function getErrorMessage(): string
    {
        return $this->stdErr->getLine(0);
    }
}
