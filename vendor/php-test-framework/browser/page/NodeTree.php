<?php

namespace stf\browser\page;

use tplLib\node\TagNode;
use tplLib\node\AbstractNode;

use tplLib\node\TextNode;
use tplLib\node\WsNode;

class NodeTree {

    private AbstractNode $root;

    public function __construct(AbstractNode $tree) {
        $this->root = $tree;
    }

    public function findNodesByTagNames($names) : array {
        return $this->findChildNodesByTagNames($this->root, $names);
    }

    public function findChildNodesByTagNames($node, $names) : array {
        $nodeList = array_filter($this->getChildTagNodes($node), function ($each) use ($names) {
            return in_array(strtolower($each->getTagName()), $names);
        });

        return array_values($nodeList);
    }

    public function getAllTagNodes() : array {
        return $this->getChildTagNodes($this->root);
    }

    private function getChildTagNodes(AbstractNode $node) : array {
        $result = [];

        if ($node instanceof TagNode) {
            $result[] = $node;
        }

        foreach ($node->getChildren() as $child) {
            $result = array_merge(
                $result,
                $this->getChildTagNodes($child));
        }

        return $result;
    }

    private function getText($node) : string {
        return join("\n", $this->getTextLines($node));
    }

    public function getFullText() : string {
        return $this->getText($this->root);
    }

    public function getTextLines($node, $withWhiteSpace = false) : array {
        if ($node instanceof TextNode) {
            return [$node->getText()];
        } else if ($withWhiteSpace && $node instanceof WsNode) {
            return [$node->getText()];
        }

        $childTexts = [];
        foreach ($node->getChildren() as $child) {
            $childTextLines = $this->getTextLines($child, $withWhiteSpace);
            $childTexts = [...$childTexts, ...$childTextLines];
        }

        return $childTexts;
    }


}


