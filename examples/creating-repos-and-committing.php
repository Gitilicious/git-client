<?php

namespace Gitilicious\GitClient;

use Gitilicious\GitClient\Cli\Client as CliClient;
use Gitilicious\GitClient\Cli\Input\Binary;
use Gitilicious\GitClient\FileSystem\Directory;
use Gitilicious\GitClient\Git\Author;
use Gitilicious\GitClient\Git\Repository;
use Gitilicious\GitClient\FileSystem\File;

require_once __DIR__ . '/../vendor/autoload.php';

$client = new Client(new CliClient(new Binary('/usr/bin/git')));

$bareRepository = Repository::createBare($client, Directory::create('/var/git/bare'), 'Gitilicious.git');
$workRepository = $bareRepository->clone(Directory::create('/var/git/work'), 'Gitilicious');

$readme = File::create($workRepository->getPath() . '/README.md', "# My awesome repo\n");

$workRepository->add($readme);
$workRepository->commit(new Author('Git User', 'git@example.com'), 'Added readme');
$workRepository->push();
