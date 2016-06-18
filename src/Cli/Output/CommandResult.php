<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Cli\Output;

class Result
{
    private $exitCode = 0;

    private $stdOut;

    private $stdErr;

    public function __construct(int $exitCode, string $stdOut, string $stdErr)
    {
        $this->exitCode = $exitCode;
        $this->stdOut   = new Output($stdOut);
        $this->stdErr   = new Output($stdErr);
    }

    public function isSuccess(): bool
    {
        return $this->exitCode === 0;
    }

    public function getOutput(): Output
    {
        return $this->stdOut;
    }

    public function getErrorMessage(): string
    {
        return $this->stdErr->getLine(1);
    }
}
