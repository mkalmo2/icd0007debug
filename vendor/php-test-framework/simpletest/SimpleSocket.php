<?php

require_once __DIR__ . '/SimpleStickyError.php';

class SimpleSocket extends SimpleStickyError {

    private $handle;
    private bool $is_open = false;
    private string $sent = '';
    private int $block_size;

    public function __construct($host, $port, $timeout, $block_size = 255) {

        if (! ($this->handle = $this->openSocket($host, $port, $error_number, $error, 0))) {
            $this->setError("Cannot open [$host:$port] with [$error] within [$timeout] seconds");
            return;
        }

        stream_set_timeout($this->handle, $timeout);

        $this->is_open = true;
        $this->block_size = $block_size;
    }

    public function write($message): bool {
        if ($this->isError() || ! $this->isOpen()) {
            return false;
        }
        $count = fwrite($this->handle, $message);
        if (! $count) {
            if ($count === false) {
                $this->setError('Cannot write to socket');
                $this->close();
            }
            return false;
        }
        fflush($this->handle);
        $this->sent .= $message;
        return true;
    }

    public function readAll(): string {
        return stream_get_contents($this->handle);
    }

    public function isOpen(): bool {
        return $this->is_open;
    }

    public function close(): bool {
        $this->is_open = false;
        return !is_resource($this->handle) || fclose($this->handle);
    }

    public function getSent(): string {
        return $this->sent;
    }

    protected function openSocket($host, $port, &$error_number, &$error, $timeout) {
        return fsockopen($host, $port, $error_number, $error, $timeout);
    }
}
