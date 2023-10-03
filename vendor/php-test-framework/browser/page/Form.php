<?php

namespace stf\browser\page;

class Form {

    private string $action = '';
    private string $method = '';
    private string $enctype = '';
    private array $fields = [];
    private array $buttons = [];

    public function __construct(string $action, string $method, string $enctype) {
        $this->action = $action;
        $this->method = $method;
        $this->enctype = $enctype;
    }

    public function addField(AbstractInput $field): void {
        $this->fields[] = $field;
    }

    public function addTextField(string $name, string $value): void {
        $this->fields[] = new TextField($name, $value);
    }

    public function addButton($button): void {
        $this->buttons[] = $button;
    }

    public function getFields(): array {
        return $this->fields;
    }

    public function getButtonByName(string $buttonName): ?Button {
        return $this->getButtonByNameAndValue($buttonName, null);
    }

    public function getButtonByNameAndValue(
        string $buttonName, ?string $buttonValue) : ?Button {

        $buttons = array_filter($this->buttons,
            function ($button) use ($buttonName, $buttonValue) {

                return $buttonValue === null
                    ? $button->getName() === $buttonName
                    : $button->getName() === $buttonName
                    && $button->getValue() === $buttonValue;
            });

        $button = array_shift($buttons);

        return $button ?? null;
    }

    public function deleteFieldByName($fieldName) {
        $fields = array_filter($this->fields, function ($field) use ($fieldName) {
            return $field->getName() !== $fieldName;
        });

        $this->fields = array_values($fields);
    }

    public function getTextFieldByName($fieldName): ?TextField {
        return $this->getFieldByNameAndType($fieldName, TextField::class);
    }

    public function containsInputByName($fieldName): bool {
        $input = [...$this->fields, ...$this->buttons];

        foreach ($input as $field) {
            if ($field->getName() === $fieldName) {
                return true;
            }
        }
        return false;
    }

    public function getFieldByNameAndType($fieldName, $type) {
        $fields = array_filter($this->fields, function ($field) use ($fieldName, $type) {
            return $field->getName() === $fieldName
                && (get_class($field) === $type || is_subclass_of($field, $type));
        });

        $field = array_shift($fields);

        return $field ?? null;
    }

    public function getRadioByName($fieldName): ?RadioGroup {
        return $this->getFieldByNameAndType($fieldName, RadioGroup::class);
    }

    public function getFieldByName($fieldName): ?AbstractInput {
        return $this->getFieldByNameAndType($fieldName, AbstractInput::class);
    }

    public function getCheckboxByName($fieldName): ?Checkbox {
        return $this->getFieldByNameAndType($fieldName, Checkbox::class);
    }

    public function getSelectByName($fieldName): ?Select {
        return $this->getFieldByNameAndType($fieldName, Select::class);
    }

    public function getAction(): string {
        return $this->action;
    }

    public function getEnctype(): string {
        return $this->enctype;
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function __toString(): string {
        $elements = [...$this->fields, ...$this->buttons];

        $strings = array_map(function ($each) {
            return "  " . $each->__toString();
        }, $elements);

        return "Form: " . PHP_EOL
            . join(PHP_EOL, $strings) . PHP_EOL;
    }
}
