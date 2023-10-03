<?php

namespace stf\browser\page;
use function stf\decode_html_entities;

class PageBuilder {

    private NodeTree $nodeTree;
    private string $source;

    public function __construct(NodeTree $nodeTree, string $source) {
        $this->nodeTree = $nodeTree;
        $this->source = $source;
    }

    function getPage(): Page {
        $text = decode_html_entities($this->nodeTree->getFullText());

        $formBuilder = new FormBuilder($this->nodeTree);

        $page = new Page($this->source, $text,
            $this->getLinks(), $formBuilder->getFormSet());

        $page->setElements($this->getAllElements());

        return $page;
    }

    private function getAllElements(): array {
        $nodes = $this->nodeTree->getAllTagNodes();

        return array_map(function ($node) {
            $innerText = (new NodeTree($node))->getFullText();
            return new Element($node, $innerText);
        }, $nodes);
    }

    private function getLinks(): array {
        $nodes = $this->nodeTree->findNodesByTagNames(['a']);

        return array_map(function ($linkNode) {
            return new Link($this->getLinkText($linkNode),
                $linkNode->getAttributeValue('href') ?? '',
                $linkNode->getAttributeValue('id') ?? '');
        }, $nodes);
    }

    private function getLinkText($linkNode): string {
        return join("", $this->nodeTree->getTextLines($linkNode, true));
    }
}


