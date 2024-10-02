 <?php

class SimpleStickyError {

    private string $error = '';
    private string $errorCode = '';

    public function __construct() {
    }

    public function isError(): bool {
        return ($this->error != '');
    }

    public function getError(): string {
        return $this->error;
    }

    public function getErrorCode(): string {
        return $this->errorCode;
    }

    public function setError($error): void {
        $this->error = $error;
    }

    public function setErrorCode($errorCode): void {
        $this->errorCode = $errorCode;
    }

    public function clearError(): void {
        $this->setError('');
    }
 }
