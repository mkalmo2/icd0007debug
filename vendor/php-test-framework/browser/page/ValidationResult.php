<?php

namespace stf\browser\page;

class ValidationResult {

    private int $line;
    private string $message;
    private int $column;
    private string $source;
    private bool $isSuccess;

    public function __construct(bool $isSuccess) {
        $this->isSuccess = $isSuccess;
    }

    public static function success() : ValidationResult {
        return new ValidationResult(true);
    }

    public static function failure(
        string $message,
        int $line, int $column,
        string $source) : ValidationResult {

        $result = new ValidationResult(false);

        $result->message = $message;
        $result->line = $line;
        $result->column = $column;
        $result->source = $source;

        return $result;
    }

    public function isSuccess(): bool {
        return $this->isSuccess;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getColumn(): int {
        return $this->column;
    }

    public function getLine(): int {
        return $this->line;
    }

    public function getSource(): string {
        return $this->source;
    }

}


