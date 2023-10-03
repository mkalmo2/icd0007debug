<?php

class Employee {
    public ?string $id;
    public string $firstName;
    public string $lastName;
    public string $position;
    public bool $isAvailable = true;
    public string $profilePicture;
    public string $profilePictureContents;

    public function __construct(?string $id,
                                string  $firstName,
                                string  $lastName,
                                string  $position,
                                bool    $isAvailable,
                                string $profilePicture,
                                string $profilePictureContents) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->position = $position;
        $this->isAvailable = $isAvailable;
        $this->profilePicture = $profilePicture;
        $this->profilePictureContents = $profilePictureContents;
    }

}

