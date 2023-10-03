<?php

namespace stf\browser;

class Path {

    private array $parts;
    private bool $isAbsolute;
    private bool $endsWithSlash;

    public function __construct(string $path) {
        $this->isAbsolute = !!preg_match('/^\//', $path);
        $this->parts = array_filter(explode('/', $path));
        $this->endsWithSlash = !!preg_match('/\/$/', $path);
    }

    public function isAbsolute() : bool {
        return $this->isAbsolute;
    }

    public function asString() : string {
        if ($this->isRoot()) {
            return '/';
        }

        return ($this->isAbsolute() ? '/' : '')
            . implode('/', $this->parts)
            . ($this->endsWithSlash ? '/' : '');
    }

    public function cd(Path $other) : Path {
        if ($other->isAbsolute()) {
            return self::normalize($other);
        }

        $result = new Path('');
        $result->isAbsolute = $this->isAbsolute;
        $result->parts = array_merge($this->parts, $other->parts);
        $result->endsWithSlash = self::normalize($other)->isEmpty()
            ? $this->endsWithSlash
            : $other->endsWithSlash;

        return self::normalize($result);
    }

    public static function normalize(Path $path) : Path {
        $newParts = [];
        foreach ($path->parts as $part) {
            if ($part === '.') {
                continue;
            } else if ($part === '..') {
                array_pop($newParts);
            } else {
                array_push($newParts, $part);
            }
        }

        $result = new Path('');
        $result->parts = $newParts;
        $result->endsWithSlash = $path->endsWithSlash;
        $result->isAbsolute = $path->isAbsolute ||
            ($result->hasNoParts() && $path->endsWithSlash);

        return $result;
    }

    private function hasNoParts() : bool {
        return !count($this->parts);
    }

    public function isRoot() : bool {
        return $this->isAbsolute && $this->hasNoParts();
    }

    public function isEmpty() : bool {
        return $this->hasNoParts() && !$this->isAbsolute;
    }

}
