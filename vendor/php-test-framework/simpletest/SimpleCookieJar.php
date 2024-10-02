<?php

/**
 *    Repository for cookies. This stuff is a
 *    tiny bit browser dependent.
 *    @package SimpleTest
 *    @subpackage WebTester
 */
class SimpleCookieJar
{
    private $cookies;

    /**
     *    Constructor. Jar starts empty.
     *    @access public
     */
    public function __construct()
    {
        $this->cookies = array();
    }

    /**
     *    Removes expired and temporary cookies as if
     *    the browser was closed and re-opened.
     *    @param string/integer $now   Time to test expiry against.
     *    @access public
     */
    public function restartSession($date = false)
    {
        $surviving_cookies = array();
        for ($i = 0; $i < count($this->cookies); $i++) {
            if (! $this->cookies[$i]->getValue()) {
                continue;
            }
            if (! $this->cookies[$i]->getExpiry()) {
                continue;
            }
            if ($date && $this->cookies[$i]->isExpired($date)) {
                continue;
            }
            $surviving_cookies[] = $this->cookies[$i];
        }
        $this->cookies = $surviving_cookies;
    }

    /**
     *    Ages all cookies in the cookie jar.
     *    @param integer $interval     The old session is moved
     *                                 into the past by this number
     *                                 of seconds. Cookies now over
     *                                 age will be removed.
     *    @access public
     */
    public function agePrematurely($interval)
    {
        for ($i = 0; $i < count($this->cookies); $i++) {
            $this->cookies[$i]->agePrematurely($interval);
        }
    }

    /**
     *    Sets an additional cookie. If a cookie has
     *    the same name and path it is replaced.
     *    @param string $name       Cookie key.
     *    @param string $value      Value of cookie.
     *    @param string $host       Host upon which the cookie is valid.
     *    @param string $path       Cookie path if not host wide.
     *    @param string $expiry     Expiry date.
     *    @access public
     */
    public function setCookie($name, $value, $host = false, $path = '/', $expiry = false)
    {
        $cookie = new SimpleCookie($name, $value, $path, $expiry);
        if ($host) {
            $cookie->setHost($host);
        }
        $this->cookies[$this->findFirstMatch($cookie)] = $cookie;
    }

    public function deleteCookie($name) {
        $tmp = array_filter($this->cookies, function ($cookie) use ($name) {
            return $cookie->getName() !== $name;
        });

        $this->cookies = array_values($tmp);
    }

    /**
     *    Finds a matching cookie to write over or the
     *    first empty slot if none.
     *    @param SimpleCookie $cookie    Cookie to write into jar.
     *    @return integer                Available slot.
     *    @access private
     */
    protected function findFirstMatch($cookie)
    {
        for ($i = 0; $i < count($this->cookies); $i++) {
            $is_match = $this->isMatch(
                $cookie,
                $this->cookies[$i]->getHost(),
                $this->cookies[$i]->getPath(),
                $this->cookies[$i]->getName());
            if ($is_match) {
                return $i;
            }
        }
        return count($this->cookies);
    }

    /**
     *    Reads the most specific cookie value from the
     *    browser cookies. Looks for the longest path that
     *    matches.
     *    @param string $host        Host to search.
     *    @param string $path        Applicable path.
     *    @param string $name        Name of cookie to read.
     *    @return string             False if not present, else the
     *                               value as a string.
     *    @access public
     */
    public function getCookieValue($host, $path, $name)
    {
        $longest_path = '';
        foreach ($this->cookies as $cookie) {
            if ($this->isMatch($cookie, $host, $path, $name)) {
                if (strlen($cookie->getPath()) > strlen($longest_path)) {
                    $value = $cookie->getValue();
                    $longest_path = $cookie->getPath();
                }
            }
        }
        return (isset($value) ? $value : false);
    }

    /**
     *    Tests cookie for matching against search
     *    criteria.
     *    @param SimpleTest $cookie    Cookie to test.
     *    @param string $host          Host must match.
     *    @param string $path          Cookie path must be shorter than
     *                                 this path.
     *    @param string $name          Name must match.
     *    @return boolean              True if matched.
     *    @access private
     */
    protected function isMatch($cookie, $host, $path, $name)
    {
        if ($cookie->getName() != $name) {
            return false;
        }
        if ($host && $cookie->getHost() && ! $cookie->isValidHost($host)) {
            return false;
        }
        if (! $cookie->isValidPath($path)) {
            return false;
        }
        return true;
    }

    /**
     *    Uses a URL to sift relevant cookies by host and
     *    path. Results are list of strings of form "name=value".
     *    @param SimpleUrl $url       Url to select by.
     *    @return array               Valid name and value pairs.
     *    @access public
     */
    public function selectAsPairs(SimpleUrl $url): array {
        $pairs = array();
        foreach ($this->cookies as $cookie) {
            if ($this->isMatch($cookie, $url->getHost(), $url->getPath(), $cookie->getName())) {
                $pairs[] = $cookie->getName() . '=' . $cookie->getValue();
            }
        }
        return $pairs;
    }
}

