<?php

namespace LF\EnvDiff;

use InvalidArgumentException;
use LF\EnvDiff\IO\IOInterface;
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
     *
     * @return bool
     */
    public function actualizeEnv(Config $config)
    {
        $dist   = $config->getDist();
        $target = $config->getTarget();
        $exists = is_file($target);

        $this->io->write(sprintf('Actualize env from %s', $dist));

        try {
            $distEnv   = Env::parse($dist);
            $actualEnv = $exists ? Env::parse($target) : [];
        } catch (InvalidArgumentException $exception) {
            $this->io->write(sprintf('<error>%s, abort</error>', $exception->getMessage()));

            return true;
        }

        $actualEnv = $this->processEnv($distEnv, $actualEnv, $config->isKeepOutdatedEnv());

        if (!is_dir($dir = dirname($target))) {
            mkdir($dir, 0755, true);
        }

        ksort($actualEnv);
        file_put_contents(
            $target,
            '# This file is auto-generated during the composer install' . PHP_EOL . Env::dump($actualEnv)
        );

        $this->io->write(sprintf('<info>%s has been %s</info>', $target, $exists ? 'updated' : 'created'));

        return false;
    }

    /**
     * @param Config $config
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function showDifference(Config $config)
    {
        $dist   = $config->getDist();
        $target = $config->getTarget();

        try {
            $distEnv   = Env::parse($dist);
            $actualEnv = Env::parse($target);
        } catch (InvalidArgumentException $exception) {
            $this->io->write(sprintf('<error>%s</error>', $exception->getMessage()));

            return true;
        }

        $extraEnv   = array_diff_key($actualEnv, $distEnv);
        $missingEnv = array_diff_key($distEnv, $actualEnv);
        $changedEnv = array_diff(array_intersect_key($distEnv, $actualEnv), $actualEnv);

        if (!count($missingEnv) && !count($extraEnv) && !count($changedEnv)) {
            $this->io->write(sprintf('<info>%s and %s is identical</info>', $target, $dist));

            return false;
        }

        $this->io->write(sprintf('Diff between %s and %s files:', $target, $dist));
        $this->io->write('');

        foreach ($missingEnv as $env => $value) {
            $this->io->write(sprintf('<fg=red>- %s=%s</>', $env, $value));
        }
        foreach ($extraEnv as $env => $value) {
            $this->io->write(sprintf('<fg=green>+ %s=%s</>', $env, $value));
        }
        foreach ($changedEnv as $env => $default) {
            $this->io->write(sprintf('<fg=cyan>@ %s=%s (%s)</>', $env, $actualEnv[$env], $default));
        }

        return false;
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
