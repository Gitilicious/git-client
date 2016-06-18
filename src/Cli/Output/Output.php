<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Cli\Output;

class Output
{
    private $lines = [];

    public function __construct(string $output)
    {
        $this->lines = explode(PHP_EOL, trim($output));
    }

    public function getNumberOfLines(): int
    {
        return count($this->lines);
    }

    public function getLine(int $lineNumber): string
    {
        if (!array_key_exists($lineNumber - 1, $this->lines)) {
            // @todo or should we throw here instead?
            return '';
        }

        return $this->lines[$lineNumber - 1];
    }
}
