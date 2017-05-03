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
    public static function parse($path)
    {
        if (!is_file($path)) {
            throw new InvalidArgumentException(sprintf('The file "%s" does not exist', $path));
        }

        $file = fopen($path, 'r');

        $env = [];
        while (false === feof($file)) {
            $line = trim(fgets($file));
            // Ignore empty lines

            if (empty($line) === true || $line[0] === '#') {
                continue;
            }
            if (false === strpos($line, '=')) {
                throw new InvalidArgumentException(sprintf('Line `%s` is not valid env variable', $line));
            }

            list($name, $value) = explode('=', $line);
            $env[trim($name)] = trim($value);
        }

        return $env;
    }
}
