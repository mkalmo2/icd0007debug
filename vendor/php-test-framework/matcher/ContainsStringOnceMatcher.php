<?php

namespace stf\matcher;

class ContainsStringOnceMatcher extends AbstractMatcher {

    private string $needle;

    public function __construct(string $needle) {
        $this->needle = $needle;
    }

    public function matches($actual) : bool {
        return $this->getMatchCount($actual) === 1;
    }

    private function getMatchCount(string $haystack) : int {
        return substr_count($haystack, $this->needle);
    }

    public function getError(
        $actual, ?string $message = null) : MatcherError {

        return new MatcherError(ERROR_C05,
            sprintf("Should contain string '%s' once but found it %s times",
                $this->needle, $this->getMatchCount($actual)));
    }
}

