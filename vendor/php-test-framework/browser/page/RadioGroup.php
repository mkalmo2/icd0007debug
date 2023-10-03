<?php

namespace stf\browser\page;

use \RuntimeException;

class RadioGroup extends AbstractInput {

    private array $values = [];

    public function __construct(string $name) {
        parent::__construct($name);
    }

    public function addOption(?string $value) {
        $this->values[] = [$value, false];
    }

    public function hasOption(string $value): bool {
        foreach ($this->values as $each) {
            if ($each[0] === $value) {
                return true;
            }
        }

        return false;
    }

    public function selectOption(?string $value): void {
        foreach ($this->values as &$each) {
            if ($each[0] === $value) {
                $each[1] = true;
                return;
            }
        }

        throw new RuntimeException("unknown option: " . $value);
    }

    public function __toString(): string {
        $values = array_map(function ($each) {
            return $each[0];
        }, $this->values);

        return sprintf("RadioGroup: %s (%s) selected: %s\n",
            $this->getName(), implode(", ", $values), $this->getValue());
    }

    public function getValue(): ?string {
        foreach ($this->values as $each) {
            if ($each[1]) {
                return $each[0] ?? 'on';
            }
        }

        return null;
    }
}


