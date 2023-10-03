<?php

namespace stf\browser\page;

abstract class AbstractInput {

    private string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getName(): string {
        return $this->name;
    }

    public function isFile(): bool {
        return false;
    }

    public abstract function getValue(): ?string;

}


