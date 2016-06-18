<?php declare(strict_types=1);

namespace Gitilicious\GitClient\FileSystem;

class Directory
{
    private $path;

    public function __construct(string $path)
    {
        if (!is_dir($path)) {
            throw new NotFoundException(sprintf('The directory `%s` does not exist.', $path));
        }

        $this->path = $path;
    }

    public static function create(string $path, int $permissions = 0770): Directory
    {
        if (is_dir($path)) {
            throw new ExistsException(sprintf('The directory `%s` already exists.', $path));
        }

        @mkdir($path, $permissions, true);

        if (!is_dir($path)) {
            throw new PermissionDeniedException(error_get_last()['message'], error_get_last()['type']);
        }

        return new self($path);
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
