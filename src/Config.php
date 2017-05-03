<?php

namespace LF\EnvDiff;

class Config
{
    /** @var string */
    private $dist;

    /** @var string */
    private $target;

    /** @var bool */
    private $keepOutdatedEnv;

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
        if (empty($config['keep-outdated'])) {
            $config['keep-outdated'] = true;
        }

        $self                  = new static();
        $self->target          = $config['target'];
        $self->dist            = $config['dist'];
        $self->keepOutdatedEnv = (bool) $config['keep-outdated'];

        return $self;
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