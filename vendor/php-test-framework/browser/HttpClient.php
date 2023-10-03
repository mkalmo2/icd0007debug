<?php

namespace stf\browser;

use \SimpleGetEncoding;
use \SimpleMultipartEncoding;
use \SimplePostEncoding;
use \SimpleUrl;
use \SimpleCookieJar;
use \SimpleHttpRequest;
use \SimpleRoute;
use stf\FrameworkException;

class HttpClient {

    private SimpleCookieJar $cookieJar;

    public function __construct() {
        $this->cookieJar = new SimpleCookieJar();
    }

    function execute(HttpRequest $request) : HttpResponse {
        $url = $request->getFullUrl()->asString();

        $simpleHttpRequest = $this->createRequest($request, $url);

        $simpleHttpRequest->readCookiesFromJar($this->cookieJar, new SimpleUrl($url));

        $response = $simpleHttpRequest->fetch(REQUEST_TIMEOUT);

        if ($response->isError()) {
            throw new FrameworkException($response->getErrorCode(),
                $response->getError());
        }

        $response->getHeaders()->writeCookiesToJar(
            $this->cookieJar, new SimpleUrl($url));

        $headers = new HttpHeaders(
            $response->getHeaders()->getResponseCode(),
            $response->getHeaders()->getLocation() ?: null,
            $response->getHeaders()->getMimeType() ?: null);

        return new HttpResponse($headers, $response->getContent());
    }

    public function deleteCookie(string $cookieName) {
        $this->cookieJar->deleteCookie($cookieName);
    }

    private function createRequest(HttpRequest $request, string $url): SimpleHttpRequest {
        if (!$request->isPostMethod()) {
            return new SimpleHttpRequest(
                new SimpleRoute(new SimpleUrl($url)), new SimpleGetEncoding());
        }

        $encoding = $request->isMultipartForm()
            ? new SimpleMultipartEncoding()
            : new SimplePostEncoding();

        foreach ($request->getParameters() as $key => $value) {
            $encoding->add($key, $value);
        }

        foreach ($request->getFileParameters() as $key => $value) {
            $encoding->attach($key, $value[1], $value[0]);
        }

        return new SimpleHttpRequest(
            new SimpleRoute(new SimpleUrl($url)), $encoding);

    }

}


