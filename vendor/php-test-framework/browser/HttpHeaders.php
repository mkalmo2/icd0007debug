<?php

namespace stf\browser;

class HttpHeaders {

    public int $responseCode;
    public ?string $location;
    public ?string $contentType;

    public function __construct(int $responseCode,
                                ?string $location,
                                ?string $contentType) {
        $this->responseCode = $responseCode;
        $this->location = $location;
        $this->contentType = $contentType;
    }


}


