<?php

namespace LF\EnvDiff\Tests\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Script\Event;
use InvalidArgumentException;
use LF\EnvDiff\Composer\ScriptHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ScriptHandlerTest extends TestCase
{
    /**
     * @return array
     */
    public function provideInvalidConfiguration()
    {
        return [
            'invalid type'                      => [
                ['lf-env-diff' => 'not an array'],
                'The extra.lf-env-diff setting must be an array or a configuration object',
            ],
            'invalid type for multiple configs' => [
                ['lf-env-diff' => ['not an array']],
                'The extra.lf-env-diff setting must be an array of configuration objects',
            ],
        ];
    }

    /**
     * @dataProvider provideInvalidConfiguration()
     *
     * @param array  $extras
     * @param string $exceptionMessage
     */
    public function testActualizeEnvInvalidConfiguration(array $extras, $exceptionMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $event = $this->createEvent($extras);
        $event
            ->expects(self::never())
            ->method('getIO');

        ScriptHandler::actualizeEnv($event);
    }

    /**
     * @dataProvider provideInvalidConfiguration()
     *
     * @param array  $extras
     * @param string $exceptionMessage
     */
    public function testShowDifferenceInvalidConfiguration(array $extras, $exceptionMessage)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $event = $this->createEvent($extras);
        $event
            ->expects(self::never())
            ->method('getIO');

        ScriptHandler::showDifference($event);
    }

    /**
     * @return array
     */
    public function provideValidConfiguration()
    {
        return [
            [
                [
                    'lf-env-diff' => [
                        [
                            'dist'   => 'fixtures/difference/valid/identical/.env.dist',
                            'target' => 'fixtures/difference/valid/identical/.env'
                        ],
                        [
                            'dist'   => 'fixtures/difference/valid/identical/.env.dist',
                            'target' => 'fixtures/difference/valid/identical/.env'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider provideValidConfiguration()
     *
     * @param array $extras
     */
    public function testActualizeEnvValidConfiguration(array $extras)
    {
        $io = $this->createMock(IOInterface::class);

        $event = $this->createEvent($extras);
        $event
            ->expects(self::once())
            ->method('getIO')
            ->willReturn($io);

        chdir(dirname(__DIR__));
        ScriptHandler::actualizeEnv($event);
    }

    /**
     * @dataProvider provideValidConfiguration()
     *
     * @param array $extras
     */
    public function testShowDifferenceValidConfiguration(array $extras)
    {
        $io = $this->createMock(IOInterface::class);

        $event = $this->createEvent($extras);
        $event
            ->expects(self::once())
            ->method('getIO')
            ->willReturn($io);

        chdir(dirname(__DIR__));
        ScriptHandler::showDifference($event);
    }

    /**
     * @param array $extras
     *
     * @return PHPUnit_Framework_MockObject_MockObject | Event
     */
    private function createEvent(array $extras = [])
    {
        $package = $this->createMock(PackageInterface::class);
        $package->expects(self::once())
                ->method('getExtra')
                ->willReturn($extras);

        $composer = $this->createMock(Composer::class);
        $composer->expects(self::once())
                 ->method('getPackage')
                 ->willReturn($package);

        $event = $this->createMock(Event::class);
        $event->expects(self::once())
              ->method('getComposer')
              ->willReturn($composer);

        return $event;
    }
}
