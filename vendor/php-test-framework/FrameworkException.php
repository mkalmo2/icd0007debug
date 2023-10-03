<?php

namespace stf;

use RuntimeException;

class FrameworkException extends RuntimeException {

    public function __construct(string $code, string $message) {
        $this->code = $code;
        $this->message = $message;
    }
}

