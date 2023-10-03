<?php

namespace stf\browser\page;

class Option {

    private ?string $value;
    private string $text;
    private bool $isSelected = false;

    public function __construct(string $text, ?string $value) {
        $this->text = $text;
        $this->value = $value;
    }

    public function getText(): string {
        return $this->text;
    }

    public function getValue(): ?string {
        return $this->value ?? $this->text;
    }

    public function select() {
        $this->isSelected = true;
    }

    public function isSelected() : bool {
        return $this->isSelected;
    }

    public function unSelect() {
        $this->isSelected = false;
    }

}


