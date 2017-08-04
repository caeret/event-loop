<?php

namespace Gaemma\EventLoop;

class Factory
{

    /**
     * Create an event loop instance.
     *
     * @return LibEvLoop|\React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop
     */
    public static function create()
    {
        if (class_exists('\Ev', false)) {
            return new LibEvLoop();
        }
        return \React\EventLoop\Factory::create();
    }
}
