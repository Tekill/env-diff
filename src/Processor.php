<?php

namespace LF\EnvDiff;

use Composer\IO\IOInterface;
use InvalidArgumentException;
use LF\EnvDiff\Env\Dumper;
use LF\EnvDiff\Env\Parser;
use RuntimeException;

class Processor
{
    /** @var IOInterface */
    private $io;

    /**
     * Processor constructor.
     *
     * @param IOInterface $io
     */
    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    /**
     * @param Config $config
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function actualizeEnv(Config $config)
    {
        $dist   = $config->getDist();
        $target = $config->getTarget();

        $distEnv   = Parser::parse($dist);
        $actualEnv = is_file($target) ? Parser::parse($target) : [];

        $this->io->write(sprintf('<info>%s the "%s" file</info>', is_file($target) ? 'Check' : 'Creating', $target));

        $actualEnv = $this->processEnv($distEnv, $actualEnv, $config->isKeepOutdatedEnv());

        if (!is_dir($dir = dirname($target))) {
            mkdir($dir, 0755, true);
        }

        ksort($actualEnv);
        file_put_contents(
            $target,
            '# This file is auto-generated during the composer install' . PHP_EOL . Dumper::dump($actualEnv)
        );
    }

    /**
     * @param Config $config
     *
     * @throws InvalidArgumentException
     */
    public function showDifference(Config $config)
    {
        $dist   = $config->getDist();
        $target = $config->getTarget();

        $distEnv   = Parser::parse($dist);
        $actualEnv = is_file($target) ? Parser::parse($target) : [];

        $extraEnv   = array_diff_assoc($actualEnv, $distEnv);
        $missingEnv = array_diff_assoc($distEnv, $actualEnv);

        if (count($missingEnv)) {
            $this->io->write(
                sprintf('<warning>Your %s and %s files are not in sync.</warning>', $target, $dist)
            );
        }

        foreach ($extraEnv as $env => $value) {
            $this->io->write(sprintf('<info>- %s=%s</info>', $env, $value));
        }
        foreach ($missingEnv as $env => $value) {
            $this->io->write(sprintf('<warning>+ %s=%s</warning>', $env, $value));
        }
    }

    /**
     * @param array $expectedEnv
     * @param array $actualEnv
     * @param bool  $keepOutdated
     *
     * @return array
     *
     * @throws RuntimeException
     */
    private function processEnv(array $expectedEnv, array $actualEnv, $keepOutdated)
    {
        if (false === $keepOutdated) {
            $actualEnv = array_intersect_key($actualEnv, $expectedEnv);
        }

        return $this->getEnv($expectedEnv, $actualEnv);
    }

    /**
     * @param array $expectedEnv
     * @param array $actualEnv
     *
     * @return array
     *
     * @throws RuntimeException
     */
    private function getEnv(array $expectedEnv, array $actualEnv)
    {
        if (!$this->io->isInteractive()) {
            return array_replace($expectedEnv, $actualEnv);
        }

        $diffEnv = array_diff_key($expectedEnv, $actualEnv);
        if (count($diffEnv) > 0) {
            $this->io->write('<comment>Some env variables are missing. Please provide them.</comment>');

            foreach ($diffEnv as $env => $default) {
                $actualEnv[$env] = $this->io->ask(
                    sprintf('<question>%s</question> (<comment>%s</comment>): ', $env, $default),
                    $default
                );
            }
        }

        return $actualEnv;
    }
}
