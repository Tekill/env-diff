<?php

namespace LF\EnvDiff\IO;

interface IOInterface
{
    /**
     * @param string $message
     */
    public function write($message);

    /**
     * @return bool
     */
    public function isInteractive();

    /**
     * @param string      $question
     * @param string|null $default
     *
     * @return string
     */
    public function ask($question, $default = null);
}
