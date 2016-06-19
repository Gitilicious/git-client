<?php declare(strict_types=1);

namespace Gitilicious\GitClient\Git;

class Author
{
    private $name;

    private $emailAddress;

    public function __construct(string $name, string $emailAddress)
    {
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException(sprintf('The provided email address `%s` is not valid.', $this->emailAddress));
        }

        $this->name         = $name;
        $this->emailAddress = $emailAddress;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getFormatted(): string
    {
        return sprintf('%s <$s>', $this->name, $this->emailAddress);
    }
}
