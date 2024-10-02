<?php

namespace stf\browser\page;

use tplLib\node\TagNode;

class FormBuilder {

    private NodeTree $nodeTree;

    public function __construct(NodeTree $nodeTree) {
        $this->nodeTree = $nodeTree;
    }

    public function getFormSet(): FormSet {
        $formSet = new FormSet();

        $formNodes = $this->nodeTree->findNodesByTagNames(['form']);

        foreach ($formNodes as $formNode) {
            $formSet->addForm($this->buildForm($formNode));
        }

        return $formSet;
    }

    private function buildForm(TagNode $formNode): Form {
        $formElements = $this->nodeTree->findChildNodesByTagNames(
            $formNode, ['input', 'button', 'textarea', 'select']);

        $action = $formNode->getAttributeValue('action') ?? '';
        $method = $formNode->getAttributeValue('method') ?? '';
        $enctype = $formNode->getAttributeValue('enctype') ?? '';

        $form = new Form($action, $method, $enctype);

        $radios = [];
        foreach ($formElements as $element) {
            $name = $element->getAttributeValue('name');

            if ($this->isButton($element)) {
                $form->addButton($this->createButton($element));
            } else if ($this->isRadio($element) && $name !== null) {

                $radios[$name] ??= new RadioGroup($name);
                $radioGroup = $radios[$name];

                $value = $element->getAttributeValue('value');
                $radioGroup->addOption($value);
                if ($element->hasAttribute('checked')) {
                    $radioGroup->selectOption($value);
                }

            } else if ($this->isCheckbox($element) && $name !== null) {

                $value = $element->getAttributeValue('value');

                $checkbox = new Checkbox($name, $value,
                    $element->hasAttribute('checked'));

                $form->addField($checkbox);

            } else if ($this->isFile($element) && $name !== null) {

                $fileField = new FileField($name, '', '');

                $form->addField($fileField);

            } else if ($this->isTextArea($element) && $name !== null) {

                $value = join("", $this->nodeTree->getTextLines($element, true));

                $form->addField(new TextField($name, $value));

            } else if ($this->isSelect($element) && $name !== null) {
                $form->addField($this->createSelect($element));

            } else if ($name !== null) {
                $value = $element->getAttributeValue('value');

                $form->addField(new TextField($name, $value));
            }
        }

        foreach ($radios as $radioGroup) {
            $form->addField($radioGroup);
        }

        return $form;
    }

    private function isButton($element): bool {
        if ($element->getTagName() === 'input'
            && $element->getAttributeValue('type') === 'submit') {
            return true;
        } else if ($element->getTagName() === 'button'
            && !$element->getAttributeValue('type')) {

            return true;
        } else if ($element->getTagName() === 'button'
            && $element->getAttributeValue('type') === 'submit') {

            return true;
        }

        return false;
    }

    private function isTextArea($element): bool {
        return $element->getTagName() === 'textarea';
    }

    private function isRadio($element): bool {
        return ($element->getTagName() === 'input')
            && $element->getAttributeValue('type') === 'radio';
    }

    private function isCheckbox($element): bool {
        return ($element->getTagName() === 'input')
            && $element->getAttributeValue('type') === 'checkbox';
    }

    private function isSelect($element): bool {
        return $element->getTagName() === 'select';
    }

    private function isFile($element): bool {
        return ($element->getTagName() === 'input')
            && $element->getAttributeValue('type') === 'file';
    }

    private function createSelect($element): Select {
        $name = $element->getAttributeValue('name');
        $isMultiple = $element->hasAttribute('multiple');

        $select = new Select($name, $isMultiple);

        $options = $this->nodeTree->findChildNodesByTagNames($element, ['option']);

        foreach ($options as $option) {
            $value = $option->getAttributeValue('value');
            $label = implode('', $this->nodeTree->getTextLines($option));
            $select->addOption($value, trim($label), $option->hasAttribute('selected'));
        }

        return $select;
    }

    private function createButton($element): Button {
        $name = $element->getAttributeValue('name') ?? '';
        $value = $element->getAttributeValue('value') ?? '';
        $formAction = $element->getAttributeValue('formaction') ?? '';

        $label = $element->getTagName() === 'input'
            ? $value
            : implode('', $this->nodeTree->getTextLines($element));

        return new Button($name, $value, $label, $formAction);
    }
}
