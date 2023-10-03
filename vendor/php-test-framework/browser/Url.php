<?php

namespace stf\browser;

class Url {

    private string $host;
    private Path $path;
    private string $file;
    private string $queryString;

    public function __construct(string $url) {
        $this->extractHost($url);
        $this->extractPath($url);
        $this->extractQueryString($url);
    }

    private function extractHost($url) : void {
        $scheme = parse_url($url, PHP_URL_SCHEME) ?? '';
        $host = parse_url($url, PHP_URL_HOST) ?? '';
        $port = parse_url($url, PHP_URL_PORT) ?? '';

        $this->host = $scheme
            . ($scheme ? '://' : '')
            . $host
            . ($port ? ':' : '')
            . $port;
    }

    private function extractPath($url) : void {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        if (preg_match('/\.$/', $path)) {
            $path .= '/';
        }

        $pathRegex = '/(.*\/)?(.*)/';
        preg_match($pathRegex, $path, $matches);

        $this->path = new Path($matches[1]);
        $this->file = $matches[2];
    }

    private function extractQueryString($url) : void {
        $query = parse_url($url, PHP_URL_QUERY) ?? '';
        $fragment = parse_url($url, PHP_URL_FRAGMENT) ?? '';

        $this->queryString =
            ($query ? '?' . $query : '')
            . ($fragment ? '#' . $fragment : '');
    }

    public function asString() : string {
        if ($this->host
            && ($this->path->isEmpty() || $this->path->isRoot())
            && !$this->file
            && !$this->queryString) {

            return $this->host;
        }

        return $this->host
            . ($this->host && !$this->path->isAbsolute() ? '/' : '')
            . $this->path->asString()
            . $this->file
            . $this->queryString;
    }

    private function isEmpty() : bool {
        return !$this->host
            && $this->path->isEmpty()
            && !$this->file
            && !$this->queryString;
    }

    public function navigateTo(string $destination) : Url {
        $dest = new Url($destination);

        if ($dest->host) {
            return new Url($destination);
        } else if ($dest->isEmpty()) {
            return $this;
        }

        $newUrl = new Url('');
        $newUrl->host = $this->host;
        $newUrl->path = $this->path->cd($dest->path);

        $newUrl->file = ($dest->file || !$dest->path->isEmpty())
            ? $dest->file : $this->file;

        $newUrl->queryString = $dest->queryString;

        return $newUrl;
    }

    public function getQueryString() : string {
        return $this->queryString;
    }

    public function addRequestParameter(string $key, string $value) {
        if ($this->queryString) {
            $this->queryString .= '&';
        } else {
            $this->queryString .= '?';
        }

        $this->queryString .= "$key=$value";
    }
}
