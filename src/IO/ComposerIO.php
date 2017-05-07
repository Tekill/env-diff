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
     * {@inheritdoc}
     */
    public function write($message)
    {
        $this->io->write($message);
    }

    /**
     * {@inheritdoc}
     */
    public function isInteractive()
    {
        return $this->io->isInteractive();
    }

    /**
     * {@inheritdoc}
     */
    public function ask($question, $default = null)
    {
        return $this->ask($question, $default);
    }
}
