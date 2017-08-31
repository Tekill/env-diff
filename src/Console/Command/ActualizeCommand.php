<?php

namespace LF\EnvDiff\Console\Command;

use InvalidArgumentException;
use LF\EnvDiff\Config;
use LF\EnvDiff\Processor;
use RuntimeException;

class ActualizeCommand extends AbstractCommand
{
    /**
     * @param Processor $processor
     * @param Config    $config
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    protected function doExecute(Processor $processor, Config $config)
    {
        $processor->actualizeEnv($config);
    }
}
