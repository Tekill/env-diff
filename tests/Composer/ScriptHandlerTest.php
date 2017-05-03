<?php

namespace LF\EnvHandler\Tests\Composer;

use LF\EnvDiff\Composer\ScriptHandler;
use PHPUnit\Framework\TestCase;

class ScriptHandlerTest extends TestCase
{
    /**
     * @return array
     */
    public function provideInvalidConfiguration()
    {
        return [
            'invalid type'                   => [
                ['lf-diff-env' => 'not an array'],
                'The extra.lf-env-diff setting must be an array or a configuration object',
            ],
            'invalid type for multiple configs' => [
                ['lf-diff-env' => ['not an array']],
                'The extra.lf-env-diff setting must be an array of configuration objects',
            ],
        ];
    }

    /**
     * @dataProvider provideInvalidConfiguration()
     *
     * @param mixed  $extras
     * @param string $exceptionMessage
     */
    public function testInvalidConfiguration($extras, $exceptionMessage)
    {
        $package = $this->createMock('Composer\Package\PackageInterface');
        $package->expects(self::once())
                ->method('getExtra')
                ->willReturn($extras);

        $composer = $this->createMock('Composer\Composer');
        $composer->expects(self::once())
                 ->method('getPackage')
                 ->willReturn($package);

        $event = $this->createMock('Composer\Script\Event');
        $event->expects(self::once())
              ->method('getComposer')
              ->willReturn($composer);
        $event->expects(self::never())
              ->method('getIO');

        chdir(__DIR__);

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage($exceptionMessage);

        ScriptHandler::actualizeEnv($event);
    }
}
