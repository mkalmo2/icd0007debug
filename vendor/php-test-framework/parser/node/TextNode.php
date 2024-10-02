<?php

namespace tplLib\node;

class TextNode extends AbstractNode {

    private string $text;

    public function __construct($text) {
        parent::__construct('');

        $this->text = $text;
    }

    public function getText(): string {
        return $this->text;
    }

    public function render($scope): string {
        return $scope->replaceCurlyExpression($this->text);
    }

}
