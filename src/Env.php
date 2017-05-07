<?php

namespace LF\EnvDiff;

use InvalidArgumentException;
use LF\EnvDiff\Env\Dumper;
use LF\EnvDiff\Env\Parser;

class Env
{
    /**
     * @param array $envArray
     *
     * @return string
     */
    public static function dump(array $envArray)
    {
        return (new Dumper())->dump($envArray);
    }

    /**
     * @param string $path
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public static function parse($path)
    {
        return (new Parser())->parse($path);
    }
}
