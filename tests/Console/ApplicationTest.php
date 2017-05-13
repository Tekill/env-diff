<?php

namespace LF\EnvDiff\Tests\Console;

use LF\EnvDiff\Console\Application;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testCreation()
    {
        $application = new Application();

        self::assertTrue($application->has('diff'));
        self::assertTrue($application->has('actualize'));
        self::assertTrue($application->isAutoExitEnabled());
    }
}
