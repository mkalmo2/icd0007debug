<?php

namespace stf\browser\page;

use tplLib\node\TagNode;

class Element {

    private TagNode $node;
    private string $innerText;

    public function __construct(TagNode $node, string $innerText) {
        $this->node = $node;
        $this->innerText = $innerText;
    }

    public function getId(): ?string {
        return $this->node->getAttributeValue('id');
    }

    public function getAttributeValue(string $name): ?string {
        return $this->node->getAttributeValue($name);
    }

    public function getTagName(): string {
        return $this->node->getTagName();
    }

    public function getInnerText(): string {
        return $this->innerText;
    }

}


