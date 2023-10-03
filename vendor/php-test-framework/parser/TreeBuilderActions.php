<?php

namespace tplLib;

use tplLib\node\MiscNode;
use tplLib\node\RootNode;
use tplLib\node\TagNode;
use tplLib\node\TextNode;
use tplLib\node\WsNode;

class TreeBuilderActions {

    private $stack;

    public function __construct() {
        $this->stack = [];
        $this->stack[] = new RootNode();
    }

    public function getResult() {
        list ($first) = $this->stack;
        return $first;
    }

    private function currentNode() {
        return $this->stack[count($this->stack) - 1];
    }

    public function tagStartAction($tagName, $attributes) {
        $node = $this->createTag($tagName, $attributes);

        $this->currentNode()->addChild($node);

        $this->stack[] = $node;
    }

    private function createTag($tagName, $attributes) : TagNode {
        return new TagNode($tagName, $attributes);
    }

    public function tagEndAction($tagName) {
        array_pop($this->stack);
    }

    public function voidTagAction($tagName, $attributes, $hasSlashClose) {
        $node = $this->createTag($tagName, $attributes);

        $node->makeVoid();

        if ($hasSlashClose) {
            $node->addSlashClose();
        }

        $this->currentNode()->addChild($node);
    }

    public function staticElementAction($token) {

        if ($token->type === HtmlLexer::HTML_TEXT) {
            $node = new TextNode($token->text);
        } else if ($token->type === HtmlLexer::SEA_WS) {
            $node = new WsNode($token->text);
        } else {
            $node = new MiscNode($token->text);
        }

        $this->currentNode()->addChild($node);
    }
}

