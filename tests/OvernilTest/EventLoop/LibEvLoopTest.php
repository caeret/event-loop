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
        $filename = '/tmp/' . md5(__METHOD__);
        if (!is_file($filename)) {
            touch($filename);
        }
        return fopen($filename, 'r+');
    }
}
