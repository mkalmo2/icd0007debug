<?php

class Employee {
    public ?string $id;
    public string $firstName;
    public string $lastName;
    public string $position;
    public string $profilePicture;
    public string $profilePictureContents;

    public function __construct(?string $id,
                                string  $firstName,
                                string  $lastName,
                                string  $position,
                                string $profilePicture,
                                string $profilePictureContents) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->position = $position;
        $this->profilePicture = $profilePicture;
        $this->profilePictureContents = $profilePictureContents;
    }

}

