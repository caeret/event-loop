<?php

namespace Gaemma\EventLoop;

class PidFile
{

    /**
     * The lock file path.
     *
     * @var string
     */
    private $lockFile;

    /**
     * The lock file handle.
     *
     * @var resource
     */
    private $_lockHandle;

    /**
     * PidFile constructor.
     *
     * @param string $lockFile
     */
    public function __construct($lockFile)
    {
        $this->lockFile = $lockFile;
    }

    /**
     * Initialize a pid file.
     */
    public function initialize()
    {
        if ($this->isActive()) {
            throw new \RuntimeException('The process is already initialized.');
        }
        $handle = $this->getLockHandle();
        if (!@flock($handle, LOCK_EX | LOCK_NB)) {
            throw new \RuntimeException('Unable to lock the file.');
        }
        if (@fseek($handle, 0) === -1) {
            throw new \RuntimeException('Unable to seek the file.');
        }
        if (!@ftruncate($handle, 0)) {
            throw new \RuntimeException('Unable to truncate the file.');
        }
        if (!@fwrite($handle, posix_getpid() . PHP_EOL)) {
            throw new \RuntimeException('Unable to write pid to the file.');
        }
    }

    /**
     * Finalize a pid file.
     */
    public function finalize()
    {
        $handle = $this->getLockHandle();
        @flock($handle, LOCK_UN);
        @fclose($handle);
        @unlink($handle);
    }

    /**
     * Check if the process is active.
     *
     * @return bool
     */
    public function isActive()
    {
        if ($pid = $this->getPid()) {
            return posix_kill($pid, 0);
        }
        return false;
    }

    /**
     * Get the running process pid.
     *
     * @return int|null return the  pid of the process.
     */
    public function getPid()
    {
        $handle = $this->getLockHandle();
        $content = fgets($handle);
        if ($content) {
            return (int)$content;
        }
        return null;
    }

    /**
     * Get the lock handle.
     *
     * @return resource
     */
    private function getLockHandle()
    {
        if (!isset($this->_lockHandle)) {
            $handle = @fopen($this->lockFile, 'a+');
            if (!$handle) {
                throw new \RuntimeException($this->getErrorMessage('Unable to open lock file.'));
            }
            $this->_lockHandle = $handle;
        }
        return $this->_lockHandle;
    }

    /**
     * Get an error message from the default message or the suppressed error.
     *
     * @param string $defaultMessage
     * @return string
     */
    private function getErrorMessage($defaultMessage)
    {
        $error = error_get_last();
        if ($error && isset($error['message'])) {
            return $error['message'];
        }
        return $defaultMessage;
    }
}
