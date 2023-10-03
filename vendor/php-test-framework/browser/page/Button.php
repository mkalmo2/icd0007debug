<?php

namespace stf\browser\page;

class Button extends AbstractInput {

    private string $formAction;
    private string $value;
    private string $label;

    public function __construct(
        string $name, string $value, string $label, string $formAction) {

        parent::__construct($name);
        $this->value = $value;
        $this->label = $label;
        $this->formAction = $formAction;
    }

    public function __toString() : string {
        return sprintf("Button: %s %s %s",
            $this->getName(), $this->getValue(), $this->formAction);
    }

    public function getValue(): string {
        return $this->value;
    }

    public function getLabel(): string {
        return $this->label;
    }

    public function getFormAction(): string {
        return $this->formAction;
    }
}


