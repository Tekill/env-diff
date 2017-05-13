<?php

namespace LF\EnvDiff\Tests\Console\Command;

use LF\EnvDiff\Console\Command\ActualizeCommand;
use LF\EnvDiff\Console\Command\DiffCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;

class CommandsTest extends TestCase
{
    /**
     * @return array
     */
    public function dataRun()
    {
        return [
            'diff'      => [new DiffCommand('name')],
            'actualize' => [new ActualizeCommand('name')],
        ];
    }

    /**
     * @dataProvider dataRun()
     *
     * @param Command $command
     */
    public function testRun(Command $command)
    {
        $input  = $this
            ->getMockBuilder(Input::class)
            ->disableOriginalConstructor()
            ->getMock();
        $output = $this
            ->getMockBuilder(Output::class)
            ->disableOriginalConstructor()
            ->setMethods(['write', 'doWrite'])
            ->getMock();
        $output->expects(self::atLeastOnce())
               ->method('write');

        self::assertEquals(
            0,
            $command->run($input, $output)
        );
    }
}
