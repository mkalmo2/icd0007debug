<?php

namespace stf\browser\page;

use \RuntimeException;

class Select extends AbstractInput {

    private array $options = [];
    private bool $isMultiple;

    public function __construct(string $name, bool $isMultiple = false) {
        parent::__construct($name);
        $this->isMultiple = $isMultiple;
    }

    public function addOption(?string $value, string $text, bool $selected) {
        $option = new Option($text, $value);

        if ($selected) {
            $option->select();
        }

        $this->options[] = $option;
    }

    public function hasOptionWithLabel(string $text) : bool {
        foreach ($this->options as $each) {
            if ($each->getText() === $text) {
                return true;
            }
        }

        return false;
    }

    public function hasOptionWithValue(string $value): bool {
        foreach ($this->options as $each) {
            if ($each->getValue() === $value) {
                return true;
            }
        }

        return false;
    }

    public function isMultiple(): bool {
        return $this->isMultiple;
    }

    public function selectOptionWithText(string $text): void {
        foreach ($this->options as $each) {
            $each->unSelect();
        }

        foreach ($this->options as $each) {
            if ($each->getText() === $text) {
                $each->select();
                return;
            }
        }

        throw new RuntimeException("unknown option text: " . $text);
    }

    public function selectOptionWithValue(string $value): void {
        foreach ($this->options as $each) {
            $each->unSelect();
        }

        foreach ($this->options as $each) {
            if ($each->getValue() === $value) {
                $each->select();
                return;
            }
        }

        throw new RuntimeException("unknown option value: " . $value);
    }

    public function getSelectedOptionText(): string {
        if (count($this->options) < 1) {
            return '';
        }

        foreach ($this->options as $each) {
            if ($each->isSelected()) {
                return $each->getText();
            }
        }

        return $this->options[0]->getText();
    }

    public function __toString(): string {
        $values = array_map(function ($each) {
            return $each->getValue();
        }, $this->options);

        return sprintf("Select: %s (%s) selected: %s\n",
            $this->getName(), implode(", ", $values), $this->getValue());
    }

    public function getValue(): ?string {
        if (!count($this->options)) {
            return null;
        }

        foreach (array_reverse($this->options) as $each) {
            if ($each->isSelected()) {
                return $each->getValue();
            }
        }

        return $this->isMultiple
            ? ''
            : $this->options[0]->getValue();
    }

    public function getOptionValues(): array {
        return array_map(function ($each) {
            return $each->getValue();
        }, $this->options);
    }
}
