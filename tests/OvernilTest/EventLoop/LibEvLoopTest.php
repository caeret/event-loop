<?php

namespace OvernilTest\EventLoop;

use Overnil\EventLoop\LibEvLoop;
use React\Tests\EventLoop\AbstractLoopTest;

class LibEvLoopTest extends AbstractLoopTest
{

    public function createLoop()
    {
        if (!class_exists('Ev')) {
            $this->markTestSkipped('libev tests skipped because ext-libev is not installed.');
        }

        return new LibEvLoop();
    }

    public function testLibEvConstructor()
    {
        $loop = new LibEvLoop();
    }

    public function createStream()
    {
        $filename = __DIR__ . '/mock/stream';
        return fopen($filename, 'r+');
    }
}
