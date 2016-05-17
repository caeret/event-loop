<?php
// Set timezone
date_default_timezone_set('Asia/Shanghai');
// Prevent session cookies
ini_set('session.use_cookies', 0);
// Enable Composer autoloader
/**
 * @var \Composer\Autoload\ClassLoader $autoloader
*/
$autoloader = include dirname(__DIR__) . '/vendor/autoload.php';
// Register test classes
$autoloader->addPsr4('OvernilTest\\', __DIR__);
$autoloader->addPsr4('React\\Tests\\EventLoop\\', dirname(__DIR__) . '/vendor/react/event-loop/tests');
