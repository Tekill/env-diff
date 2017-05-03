<?php

namespace LF\EnvHandler\Tests;

use Composer\IO\IOInterface;
use LF\EnvDiff\Config;
use LF\EnvDiff\Processor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ProcessorTest extends TestCase
{
    /** @var IOInterface | \PHPUnit_Framework_MockObject_MockObject */
    private $io;

    /** @var Processor */
    private $processor;

    protected function setUp()
    {
        parent::setUp();

        $this->io        = $this->createMock('Composer\IO\IOInterface');
        $this->processor = new Processor($this->io);
    }

    /**
     * @return array
     */
    public function provideInvalidConfiguration()
    {
        return [
            'no file' => [
                Config::createFormArray(),
                'The file ".env.dist" does not exist',
            ],
        ];
    }

    /**
     * @dataProvider provideInvalidConfiguration
     *
     * @param Config $config
     * @param string $exceptionMessage
     */
    public function testInvalidConfiguration(Config $config, $exceptionMessage)
    {
        $this->expectExceptionMessage($exceptionMessage);

        $this->processor->actualizeEnv($config);
    }

    /**
     * @return array
     */
    public function actualizeEnvDataProvider()
    {
        $config = Config::createFormArray([
            'dist'   => '.env.dist',
            'target' => '.env'
        ]);
        $tests  = [];

        foreach (glob(__DIR__ . '/fixtures/testcases/*/') as $folder) {
            $tests[basename($folder)]                  = [$folder, $config, false];
            $tests[basename($folder) . ' interactive'] = [$folder, $config, true];
        }

        return $tests;
    }

    /**
     * @dataProvider actualizeEnvDataProvider()
     *
     * @param string $directory
     * @param Config $config
     * @param bool   $isInteractive
     */
    public function testActualizeEnv($directory, Config $config, $isInteractive)
    {
        $workingDir = sys_get_temp_dir() . '/lf_env_dot_handler/';
        $exists     = $this->initializeTestCase($config, $directory, $workingDir);

        $message = sprintf('<info>%s the "%s" file</info>', $exists ? 'Check' : 'Creating', $config->getTarget());
        $this->io->expects(self::once())
                 ->method('isInteractive')
                 ->willReturn($isInteractive);
        $this->io->expects(self::any())
                 ->method('write')
                 ->willReturn($message);
        $this->io->expects(self::any())
                 ->method('ask')
                 ->willReturnArgument(1);

        $this->processor->actualizeEnv($config);

        self::assertFileEquals(
            $directory . '/.expected',
            $workingDir . '/' . $config->getTarget()
        );
    }

    private function initializeTestCase(Config $config, $dataDir, $workingDir)
    {
        $fs = new Filesystem();

        if (is_dir($workingDir)) {
            $fs->remove($workingDir);
        }

        $fs->copy($dataDir . $config->getDist(), $workingDir . $config->getDist());

        if ($exists = file_exists($dataDir . '/' . $config->getTarget())) {
            $fs->copy($dataDir . $config->getTarget(), $workingDir . $config->getTarget());
        }

        chdir($workingDir);

        return $exists;
    }
}
