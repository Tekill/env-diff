<?php

namespace LF\EnvDiff;

class Config
{
    const DEFAULT_TARGET = '.env';
    const DEFAULT_DIST   = '.env.dist';

    /** @var string */
    private $dist;

    /** @var string */
    private $target;

    /** @var bool */
    private $keepOutdatedEnv;

    /**
     * Config constructor.
     *
     * @param string $dist
     * @param string $target
     * @param bool   $keepOutdatedEnv
     */
    public function __construct($dist = self::DEFAULT_DIST, $target = self::DEFAULT_TARGET, $keepOutdatedEnv = true)
    {
        $this->dist            = $dist;
        $this->target          = $target;
        $this->keepOutdatedEnv = $keepOutdatedEnv;
    }

    /**
     * @param array $config
     *
     * @return static
     */
    public static function createFormArray(array $config = [])
    {
        if (empty($config['target'])) {
            $config['target'] = '.env';
        }
        if (empty($config['dist'])) {
            $config['dist'] = $config['target'] . '.dist';
        }
        if (!isset($config['keep-outdated'])) {
            $config['keep-outdated'] = true;
        }

        return new static($config['dist'], $config['target'], (bool) $config['keep-outdated']);
    }

    /**
     * @return string
     */
    public function getDist()
    {
        return $this->dist;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return boolean
     */
    public function isKeepOutdatedEnv()
    {
        return $this->keepOutdatedEnv;
    }
}
