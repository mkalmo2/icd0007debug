<?php

namespace tplLib\node;

class WsNode extends AbstractNode {

    private string $text;

    public function __construct(string $text) {
        parent::__construct('');

        $this->text = $text;
    }

    public function getText() : string {
        return $this->text;
    }

    public function render($scope) : string {
        return $this->text;
    }

}
