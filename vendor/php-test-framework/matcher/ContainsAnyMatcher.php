<?php

namespace stf\matcher;

class ContainsAnyMatcher extends AbstractMatcher {

    private array $options;

    public function __construct(array $options) {
        $this->options = $options;
    }

    public function matches($actual) : bool {
        foreach ($this->options as $option) {
            if ($actual === $option) {
                return true;
            }
        }

        return false;
    }

    public function getError(
        $actual, ?string $message = null) : MatcherError {

        return new MatcherError(ERROR_C07,
            sprintf("'%s' did not match any of expected options.", $actual));
    }
}

