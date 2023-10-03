<?php

namespace stf\browser\page;

use function stf\decode_html_entities;

class FileField extends AbstractInput {

    private ?string $path;
    private ?string $contents;

    public function __construct(string $name, ?string $path, ?string $contents) {
        parent::__construct($name);
        $this->path = $path;
        $this->contents = $contents;
    }

    public function __toString() : string {
        return sprintf("File Input %s=%s",
            $this->getName(), $this->path);
    }

    public function getValue(): ?string {
        return decode_html_entities($this->contents ?? '');
    }

    public function setContents(?string $contents): void {
        $this->contents = $contents;
    }

    public function setPath(?string $path): void {
        $this->path = $path;
    }
    public function getPath(): string {
        return $this->path;
    }

    public function getContents(): string {
        return $this->contents;
    }

    public function isFile(): bool {
        return true;
    }

}


