<?php

namespace LF\EnvDiff\Composer;

use Composer\Script\Event;
use InvalidArgumentException;
use LF\EnvDiff\Config;
use LF\EnvDiff\IO\ComposerIO;
use LF\EnvDiff\Processor;
use RuntimeException;

class ScriptHandler
{
    const CONFIG_KEY = 'lf-env-diff';

    /**
     * @param Event $event
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public static function actualizeEnv(Event $event)
    {
        $configs   = self::extractConfigs($event);
        $processor = new Processor(new ComposerIO($event->getIO()));

        foreach ($configs as $config) {
            $processor->actualizeEnv(Config::createFormArray($config));
        }
    }

    /**
     * @param Event $event
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public static function showDifference(Event $event)
    {
        $configs   = self::extractConfigs($event);
        $processor = new Processor(new ComposerIO($event->getIO()));

        foreach ($configs as $config) {
            $processor->showDifference(Config::createFormArray($config));
        }
    }

    /**
     * @param Event $event
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    private static function extractConfigs(Event $event)
    {
        $extras = $event->getComposer()->getPackage()->getExtra();

        $configs = isset($extras[self::CONFIG_KEY]) ? $extras[self::CONFIG_KEY] : [[]];

        if (!is_array($configs)) {
            throw new InvalidArgumentException(
                'The extra.lf-env-diff setting must be an array or a configuration object'
            );
        }

        foreach ($configs as $config) {
            if (!is_array($config)) {
                throw new InvalidArgumentException(
                    'The extra.lf-env-diff setting must be an array of configuration objects'
                );
            }
        }

        return $configs;
    }
}
