<?php

namespace stf\browser;

class HttpResponse {

    private HttpHeaders $headers;
    private string $contents;

    public function __construct(HttpHeaders $headers, string $contents) {
        $this->headers = $headers;
        $this->contents = $contents;
    }

    public function isRedirect(): bool {
        $code = $this->headers->responseCode ?? 0;

        return $code >= 300 && $code < 400;
    }

    public function getLocation(): string {
        return $this->headers->location;
    }

    public function getResponseCode(): int {
        return $this->headers->responseCode;
    }

    public function getContentType(): string {
        return $this->headers->contentType;
    }

    public function getContents(): string {
        return $this->contents;
    }

}


