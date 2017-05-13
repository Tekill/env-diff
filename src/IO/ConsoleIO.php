<?php

namespace LF\EnvDiff\IO;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @codeCoverageIgnore
 */
class ConsoleIO implements IOInterface
{
    /** @var InputInterface */
    private $input;

    /** @var OutputInterface */
    private $output;

    /** @var QuestionHelper */
    private $questionHelper;

    /**
     * ConsoleIO constructor.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input          = $input;
        $this->output         = $output;
        $this->questionHelper = new QuestionHelper();
    }

    /**
     * {@inheritdoc}
     */
    public function write($message)
    {
        $this->output->writeln($message);
    }

    /**
     * {@inheritdoc}
     */
    public function isInteractive()
    {
        return $this->input->isInteractive();
    }

    /**
     * {@inheritdoc}
     */
    public function ask($question, $default = null)
    {
        return $this->questionHelper->ask($this->input, $this->output, new Question($question, $default));
    }
}
