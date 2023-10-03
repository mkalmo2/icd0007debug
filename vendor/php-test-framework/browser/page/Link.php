<?php

namespace stf\browser\page;

class Link {

    private string $text;
    private string $href;
    private string $id;

    public function __construct(string $text, string $href, string $id) {
        $this->text = $text;
        $this->href = $href;
        $this->id = $id;
    }

    public function getText() : ?string {
        return $this->text;
    }

    public function getHref() : ?string {
        return $this->href;
    }

    public function getId() : ?string {
        return $this->id;
    }

    public function __toString() : string {
        return sprintf('<a href="%s">%s</a>',
            $this->href, $this->text);
    }

}


