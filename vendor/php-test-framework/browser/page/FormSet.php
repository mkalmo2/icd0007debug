<?php

namespace stf\browser\page;

use stf\FrameworkException;

class FormSet {

    private array $forms = [];

    public function addForm(Form $form) {
        $this->forms[] = $form;
    }

    public function getFormCount() : int {
        return count($this->forms);
    }

    public function getButtonByName(string $buttonName) : ?Button {
        return $this->getButtonByNameAndValue($buttonName, null);
    }

    public function getButtonByNameAndValue(
        string $buttonName, ?string $buttonValue) : ?Button {

        $form = $this->findFormContainingField($buttonName);

        return $form
            ? $form->getButtonByNameAndValue($buttonName, $buttonValue)
            : null;
    }

    public function getTextFieldByName($fieldName) : ?TextField {
        return $this->getFieldByNameAndType($fieldName, TextField::class);
    }

    public function getFieldByNameAndType(string $fieldName, $type) {
        $form = $this->findFormContainingField($fieldName);

        return $form ? $form->getFieldByNameAndType($fieldName, $type) : null;
    }

    public function findFormContainingField($fieldName) : ?Form {
        $formsContainingField = [];

        foreach ($this->forms as $form) {
            if ($form->containsInputByName($fieldName)) {
                $formsContainingField[] = $form;
            }
        }

        if (empty($formsContainingField)) {
            return null;
        }

        if (count($formsContainingField) > 1) {
            throw new FrameworkException(
                ERROR_H06, 'Page contains multiple fields with name: ' . $fieldName);
        }

        return $formsContainingField[0];
    }

    public function getRadioByName($fieldName) : ?RadioGroup {
        return $this->getFieldByNameAndType($fieldName, RadioGroup::class);
    }

    public function getFieldByName($fieldName) : ?AbstractInput {
        return $this->getFieldByNameAndType($fieldName, AbstractInput::class);
    }

    public function getCheckboxByName($fieldName) : ?Checkbox {
        return $this->getFieldByNameAndType($fieldName, Checkbox::class);
    }

    public function getFileFieldByName($fieldName) : ?FileField {
        return $this->getFieldByNameAndType($fieldName, FileField::class);
    }

    public function getSelectByName($fieldName) : ?Select {
        return $this->getFieldByNameAndType($fieldName, Select::class);
    }

    public function __toString() : string {
        $strings = array_map(function ($each) {
            return "  " . $each->__toString();
        }, $this->forms);

        return "Forms: " . PHP_EOL
            . join(PHP_EOL, $strings) . PHP_EOL;
    }
}
