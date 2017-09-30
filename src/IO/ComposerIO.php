<?php

namespace LF\EnvDiff\IO;

use Composer\IO\IOInterface as ComposerIOInterface;

/**
 * @codeCoverageIgnore
 */
class ComposerIO implements IOInterface
{
    /** @var ComposerIOInterface */
    private $io;

    /**
     * ComposerIO constructor.
     *
     * @param ComposerIOInterface $io
     */
    public function __construct(ComposerIOInterface $io)
    {
        $this->io = $io;
    }

    /**
     * @param string $message
     */
    public function write($message)
    {
        $this->io->write($message);
    }

    /**
     * @return bool
     */
    public function isInteractive()
    {
        return $this->io->isInteractive();
    }

    /**
     * @param string      $question
     * @param string|null $default
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function ask($question, $default = null)
    {
        return $this->io->ask($question, $default);
    }
}
