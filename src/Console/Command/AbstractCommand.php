<?php

namespace LF\EnvDiff\Console\Command;

use LF\EnvDiff\Config;
use LF\EnvDiff\IO\ConsoleIO;
use LF\EnvDiff\Processor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addArgument('dist', InputOption::VALUE_REQUIRED, 'From file', Config::DEFAULT_DIST)
            ->addArgument('target', InputOption::VALUE_REQUIRED, 'To file', Config::DEFAULT_TARGET)
            ->addOption('keep-outdated', 'k', InputOption::VALUE_OPTIONAL, 'Keep old env variables', true);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config    = $this->createConfig($input);
        $processor = $this->createProcessor($input, $output);

        $this->doExecute($processor, $config);

        return 0;
    }

    /**
     * @param InputInterface $input
     *
     * @return Config
     */
    private function createConfig(InputInterface $input)
    {
        $dist         = $input->getArgument('dist');
        $target       = $input->getArgument('target');
        $keepOutdated = $input->getOption('keep-outdated');

        return new Config($dist, $target, $keepOutdated);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return Processor
     */
    private function createProcessor(InputInterface $input, OutputInterface $output)
    {
        return new Processor(new ConsoleIO($input, $output));
    }

    /**
     * @param Processor $processor
     * @param Config    $config
     */
    abstract protected function doExecute(Processor $processor, Config $config);
}
