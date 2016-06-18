<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Cli;

class Binary
{
    private $binary;

    public function __construct(string $binary)
    {
        if (!is_file($binary)) {
            throw new NotFoundException(sprintf('The executable `%s` could not be found.', $binary));
        }

        if (!is_executable($binary)) {
            throw new PermissionDeniedException(sprintf('`%s` is not executable.', $binary));
        }

        $this->binary = $binary;
    }
}
