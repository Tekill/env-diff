<?php

namespace LF\EnvDiff\Console\Command;

use LF\EnvDiff\Config;
use LF\EnvDiff\Processor;

class DiffCommand extends AbstractCommand
{
    /**
     * @param Processor $processor
     * @param Config    $config
     */
    protected function doExecute(Processor $processor, Config $config)
    {
        $processor->showDifference($config);
    }
}
