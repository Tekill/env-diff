<?php

namespace LF\EnvHandler\Tests;

use LF\EnvDiff\Config;
use LF\EnvDiff\IO\IOInterface;
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

        $this->io        = $this->createMock('LF\EnvDiff\IO\IOInterface');
        $this->processor = new Processor($this->io);
    }

    /**
     * @return array
     */
    public function actualizeEnvDataProvider()
    {
        $config = Config::createFormArray(
            [
                'dist'   => '.env.dist',
                'target' => '.env'
            ]
        );
        $tests  = [];

        foreach (glob(__DIR__ . '/fixtures/actualize/valid/*/') as $folder) {
            $tests[basename($folder)]                  = [$folder, $config, false];
            $tests[basename($folder) . ' interactive'] = [$folder, $config, true];
        }

        $subdirectory       = realpath(__DIR__ . '/fixtures/actualize/subdirectory/') . '/';
        $configSubdirectory = Config::createFormArray(
            [
                'dist'   => 'sub/.env.dist',
                'target' => 'expected/.env'
            ]
        );

        $tests[$subdirectory]                  = [$subdirectory, $configSubdirectory, false];
        $tests[$subdirectory . ' interactive'] = [$subdirectory, $configSubdirectory, false];

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
        $workingDir = sys_get_temp_dir() . '/lf_env_diff_tests/';
        $fs         = new Filesystem();

        if (is_dir($workingDir)) {
            $fs->remove($workingDir);
        }

        $fs->copy($directory . $config->getDist(), $workingDir . $config->getDist());

        if ($exists = file_exists($directory . '/' . $config->getTarget())) {
            $fs->copy($directory . $config->getTarget(), $workingDir . $config->getTarget());
        }

        chdir($workingDir);
        $this->io->expects(self::once())
                 ->method('isInteractive')
                 ->willReturn($isInteractive);
        $this->io->expects(self::any())
                 ->method('write');
        $this->io->expects(self::any())
                 ->method('ask')
                 ->willReturnArgument(1);

        self::assertFalse(
            $this->processor->actualizeEnv($config)
        );
        self::assertFileEquals(
            $directory . '/.expected',
            $workingDir . $config->getTarget()
        );
    }

    /**
     * @return array
     */
    public function actualizeEnvDataWithRemoveOutdatedProvider()
    {
        $tests = [];

        $subdirectory       = realpath(__DIR__ . '/fixtures/actualize/delete-old-variable/') . '/';
        $configSubdirectory = new Config('.env.dist', '.env', false);

        $tests[$subdirectory]                  = [$subdirectory, $configSubdirectory, false];
        $tests[$subdirectory . ' interactive'] = [$subdirectory, $configSubdirectory, false];

        return $tests;
    }

    /**
     * @dataProvider actualizeEnvDataWithRemoveOutdatedProvider()
     *
     * @param string $directory
     * @param Config $config
     * @param bool   $isInteractive
     */
    public function testActualizeEnvWithRemoveOutdated($directory, Config $config, $isInteractive)
    {
        $workingDir = sys_get_temp_dir() . '/lf_env_diff_tests/';
        $fs         = new Filesystem();

        if (is_dir($workingDir)) {
            $fs->remove($workingDir);
        }

        $fs->copy($directory . $config->getDist(), $workingDir . $config->getDist());

        if ($exists = file_exists($directory . '/' . $config->getTarget())) {
            $fs->copy($directory . $config->getTarget(), $workingDir . $config->getTarget());
        }

        chdir($workingDir);
        $this->io->expects(self::once())
                 ->method('isInteractive')
                 ->willReturn($isInteractive);
        $this->io->expects(self::any())
                 ->method('write');
        $this->io->expects(self::any())
                 ->method('ask')
                 ->willReturnArgument(1);

        self::assertFalse(
            $this->processor->actualizeEnv($config)
        );
        self::assertFileEquals(
            $directory . '/.expected',
            $workingDir . $config->getTarget()
        );
    }

    /**
     * @return array
     */
    public function actualizeEnvParseFailedDataProvider()
    {
        $invalidDirectory = 'fixtures/actualize/invalid/';
        $tests            = [];

        foreach (scandir(__DIR__ . '/' .  $invalidDirectory) as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }
            $tests[basename($folder)] = [
                new Config($invalidDirectory . $folder . '/.env.dist'),
                file_get_contents(__DIR__ . '/' . $invalidDirectory . $folder . '/error.txt')
            ];
        }

        return $tests;
    }

    /**
     * @dataProvider actualizeEnvParseFailedDataProvider()
     *
     * @param Config $config
     * @param string $errorMessage
     */
    public function testActualizeEnvParseFailed(Config $config, $errorMessage)
    {
        $this->io->expects(self::at(1))
                 ->method('write')
                 ->with($errorMessage);

        chdir(__DIR__);
        self::assertTrue(
            $this->processor->actualizeEnv($config)
        );
    }

    /**
     * @return array
     */
    public function showDifferenceDataProvider()
    {
        $tests = [];

        foreach (glob(__DIR__ . '/fixtures/difference/valid/*/') as $folder) {
            $tests[basename($folder)] = [$folder, file($folder . 'expected.log')];
        }

        return $tests;
    }

    /**
     * @dataProvider showDifferenceDataProvider()
     *
     * @param string $directory
     * @param array  $lines
     */
    public function testShowDifference($directory, array $lines)
    {
        $config = Config::createFormArray(
            [
                'dist'   => '.env.dist',
                'target' => '.env'
            ]
        );

        foreach ($lines as $id => $line) {
            $this->io->expects(self::at($id))
                     ->method('write')
                     ->with(trim($line));
        }

        chdir($directory);

        self::assertFalse(
            $this->processor->showDifference($config)
        );
    }

    /**
     * @return array
     */
    public function showDifferenceParseFailedDataProvider()
    {
        $invalidDirectory = 'fixtures/difference/invalid/';
        $tests            = [];

        foreach (scandir(__DIR__ . '/' . $invalidDirectory) as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }
            $tests[basename($folder)] = [
                new Config($invalidDirectory . $folder . '/.env.dist', $invalidDirectory . $folder . '/.env'),
                file_get_contents(__DIR__ . '/' . $invalidDirectory . $folder . '/error.txt')
            ];
        }

        return $tests;
    }

    /**
     * @dataProvider showDifferenceParseFailedDataProvider()
     *
     * @param Config $config
     * @param string $errorMessage
     */
    public function testShowDifferenceFailed(Config $config, $errorMessage)
    {
        $this->io->expects(self::once())
                 ->method('write')
                 ->with($errorMessage);

        chdir(__DIR__);
        self::assertTrue(
            $this->processor->showDifference($config)
        );
    }
}
