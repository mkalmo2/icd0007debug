<?php

namespace stf\browser\page;

class Checkbox extends AbstractInput {

    private ?string $value;
    private bool $isChecked;

    public function __construct(string $name, ?string $value, bool $isChecked = false) {
        parent::__construct($name);
        $this->value = $value;
        $this->isChecked = $isChecked;
    }

    public function check(bool $isChecked): void {
        $this->isChecked = $isChecked;
    }

    public function isChecked(): bool {
        return $this->isChecked;
    }

    public function __toString(): string {
        return sprintf("Checkbox: '%s', value: '%s', %s\n",
            $this->getName(), $this->value,
            $this->isChecked ? 'checked' : 'unchecked');
    }

    public function getValue(): ?string {
        if (!$this->isChecked) {
            return null;
        }

        return $this->value ?? 'on';
    }
}


