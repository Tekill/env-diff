<?php

namespace LF\EnvDiff\Tests;

use LF\EnvDiff\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @return array
     */
    public function createFromArrayDataProvider()
    {
        return [
            'default config'         => [
                [],
                new Config('.env.dist', '.env')
            ],
            'custom target'          => [
                ['target' => '.custom'],
                new Config('.custom.dist', '.custom')
            ],
            'custom target & dist'   => [
                ['target' => '.custom', 'dist' => '.env.dist'],
                new Config('.env.dist', '.custom')
            ],
            'keep-outdated is false' => [
                ['keep-outdated' => false],
                new Config('.env.dist', '.env', false)
            ]
        ];
    }

    /**
     * @dataProvider createFromArrayDataProvider()
     *
     * @param array  $array
     * @param Config $expected
     */
    public function testCreateFromArray(array $array, Config $expected)
    {
        self::assertEquals($expected, Config::createFormArray($array));
    }
}
