<?php

class Task {
    public ?string $id;
    public string $description;
    public string $estimate;

    public function __construct(?string $id,
                                string  $description,
                                string  $estimate) {
        $this->id = $id;
        $this->description = $description;
        $this->estimate = $estimate;
    }

}

