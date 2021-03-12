<?php


class Post {

    public string $id = '';
    public string $title;
    public string $text;

    public function __construct(string $title, string $text) {
        $this->title = $title;
        $this->text = $text;
    }

    public function __toString() : string {
        return sprintf('Id: %s, Title: %s, Text: %s' . PHP_EOL,
            $this->id, $this->title, $this->text);
    }

}