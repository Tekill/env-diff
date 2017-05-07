<?php

namespace LF\EnvDiff\Env;

use InvalidArgumentException;

class Parser
{
    /**
     * @param string $path
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function parse($path)
    {
        if (!is_file($path)) {
            throw new InvalidArgumentException(sprintf('The file "%s" does not exist', $path));
        }

        $file = fopen($path, 'rb');

        $number = 0;
        $env    = [];
        while (false === feof($file)) {
            $line = trim(fgets($file));
            $number++;

            if ($this->isCommentOrEmpty($line)) {
                continue;
            }
            if (false === strpos($line, '=')) {
                throw new InvalidArgumentException(
                    sprintf('Parse error at %d line: `%s` is not valid env value', $number, $line)
                );
            }

            list($name, $value) = explode('=', $line, 2);
            $env[trim($name)] = trim($value);
        }

        fclose($file);

        return $env;
    }

    /**
     * @param string $line
     *
     * @return bool
     */
    private function isCommentOrEmpty($line)
    {
        return (mb_strlen($line) === 0 || $line[0] === '#');
    }
}
