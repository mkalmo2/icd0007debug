<?php

namespace php_test_framework;

class RepositoryFile {

    public string $path;
    public string $repositoryPath;

    public function __construct(string $path, string $repositoryPath) {
        $this->path = $path;
        $this->repositoryPath = $repositoryPath;
    }

    public function getAbsolutePath(): string {
        return $this->path;
    }

    public function getRelativePath(): string {
        return substr($this->path, strlen($this->repositoryPath) + 1);
    }

    public function getName(): string {
        return basename($this->path);
    }

    public function getExtension(): string {
        return strtolower(pathinfo($this->path, PATHINFO_EXTENSION));
    }

    public function isProjectFile(): bool {
        return ! preg_match('/(^.idea|^.git|ex\\d|^vendor)\//', $this->getRelativePath());
    }

    public function isGraphicsFile(): bool {
        return preg_match('/^(png|jpg|bmp|ttf)$/', $this->getExtension());
    }


}