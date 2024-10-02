<?php

namespace stf\browser\page;

class Page {

    private string $source;
    private string $text;
    private array $links;
    private FormSet $formSet;
    private array $elements = [];

    public function __construct(
        string $source, string $text, array $links, FormSet $formSet) {

        $this->source = $source;
        $this->text = $text;
        $this->links = $links;
        $this->formSet = $formSet;
    }

    public function setElements(array $elements): void {
        $this->elements = $elements;
    }

    public function getElements(): array {
        return $this->elements;
    }

    public function getId(): ?string {
        $nodeList = array_filter($this->elements, function ($each) {
            return strtolower($each->getTagName()) === 'body';
        });

        $nodeList = array_values($nodeList);

        if (count($nodeList) < 1) {
            return null;
        }

        return $nodeList[0]->getId();
    }

    public function getFormSet(): FormSet {
        return $this->formSet;
    }

    public function getLinkById(string $id) : ?Link {
        return array_values(array_filter($this->links, function ($link) use ($id) {
            return $link->getId() === $id;
        }))[0] ?? null;
    }

    public function getLinkByText(string $text) : ?Link {
        return array_values(array_filter($this->links, function ($link) use ($text) {
            return trim($link->getText()) === $text;
        }))[0] ?? null;
    }

    public function getElementByInnerText(string $text) : ?Element {
        $elements = array_values(array_filter($this->elements, function ($element) use ($text) {
            return trim($element->getInnerText()) === $text;
        }));

        // last: find the inner element
        // outer elements have the same innerText

        return count($elements) ? $elements[array_key_last($elements)] : null;
    }

    public function getSource(): string {
        return $this->source;
    }

    public function getText(): string {
        return $this->text;
    }

}


