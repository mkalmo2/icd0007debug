<?php

require_once __DIR__ . '/SimpleSocket.php';

class SimpleSecureSocket extends SimpleSocket {

    public function __construct($host, $port, $timeout) {
        parent::__construct($host, $port, $timeout);
    }

    public function openSocket($host, $port, &$error_number, &$error, $timeout) {
        return parent::openSocket("tls://$host", $port, $error_number, $error, $timeout);
    }
}
