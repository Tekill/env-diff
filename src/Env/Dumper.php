<?php

namespace LF\EnvDiff\Env;

class Dumper
{
    /**
     * @param array $envArray
     *
     * @return string
     */
    public static function dump(array $envArray)
    {
        $dump = '';

        foreach ($envArray as $env => $variable) {
            $dump .= sprintf('%s=%s%s', $env, $variable, PHP_EOL);
        }

        return trim($dump);
    }
}