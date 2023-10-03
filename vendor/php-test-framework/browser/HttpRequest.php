<?php

namespace stf\browser;

class HttpRequest {

    private Url $baseUrl;
    private string $subPath;
    private string $method;
    private string $enctype;
    private array $parameters = [];
    private array $fileParameters = [];

    public function __construct(
        Url $baseUrl, string $subPath, string $method, string $enctype) {

        $this->baseUrl = $baseUrl;
        $this->subPath = $subPath;
        $this->method = $method;
        $this->enctype = $enctype;
    }

    public function isPostMethod(): bool {
        return strtoupper($this->method) === 'POST';
    }

    public function isMultipartForm(): bool {
        return strtolower($this->enctype) === 'multipart/form-data';
    }

    public function addParameter(string $name, string $value): void {
        $this->parameters[$name] = $value;
    }

    public function addFileParameter(string $name, string $path, string $contents): void {
        $this->fileParameters[$name] = [$path, $contents];
    }

    public function getParameters(): array {
        return $this->parameters;
    }

    public function getFileParameters(): array {
        return $this->fileParameters;
    }

    public function getFullUrl(): Url {
        $url = $this->baseUrl->navigateTo($this->subPath);

        if ($this->isPostMethod()) {
            return $url;
        }

        foreach ($this->parameters as $key => $value) {
            $url->addRequestParameter($key, urlencode($value));
        }

        return $url;
    }
}
