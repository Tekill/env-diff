<?php

namespace LF\EnvDiff\Console;

use LF\EnvDiff\Console\Command\ActualizeCommand;
use LF\EnvDiff\Console\Command\DiffCommand;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct('Env diff', '1.0.1');

        $this->setAutoExit(true);
        $this->add(new DiffCommand('diff'));
        $this->add(new ActualizeCommand('actualize'));
    }
}
