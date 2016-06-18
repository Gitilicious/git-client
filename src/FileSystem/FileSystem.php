<?php declare(strict_types=1);

namespace Gitilicious\GitClient\FileSystem;

class FileSystem
{
    public function directoryExists(string $path): bool
    {
        return is_dir($path);
    }

    public function createDirectory(string $path, int $permissions = 0770): Directory
    {
        return Directory::create($path, $permissions);
    }
}
