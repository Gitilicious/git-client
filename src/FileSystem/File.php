<?php declare(strict_types=1);

namespace Gitilicious\GitClient\FileSystem;

class File
{
    private $path;

    public function __construct(string $path)
    {
        if (!is_file($path)) {
            throw new NotFoundException(sprintf('The file `%s` does not exist.', $path));
        }

        $this->path = $path;
    }

    public static function create(string $path, string $content): File
    {
        if (is_file($path)) {
            throw new ExistsException(sprintf('The file `%s` already exists.', $path));
        }

        @file_put_contents($path, $content);

        if (!is_file($path)) {
            throw new PermissionDeniedException(error_get_last()['message'], error_get_last()['type']);
        }

        return new self($path);
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
