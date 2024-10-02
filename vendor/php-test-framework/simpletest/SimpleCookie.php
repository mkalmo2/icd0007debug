<?php

require_once __DIR__ . '/url.php';

/**
 *    Cookie data holder. Cookie rules are full of pretty
 *    arbitary stuff. I have used...
 *    http://wp.netscape.com/newsref/std/cookie_spec.html
 *    http://www.cookiecentral.com/faq/
 *    @package SimpleTest
 *    @subpackage WebTester
 */
class SimpleCookie
{
    private $host;
    private $name;
    private $value;
    private $path;
    private $expiry;
    private $is_secure;

    /**
     *    Constructor. Sets the stored values.
     *    @param string $name            Cookie key.
     *    @param string $value           Value of cookie.
     *    @param string $path            Cookie path if not host wide.
     *    @param string $expiry          Expiry date as string.
     *    @param boolean $is_secure      Currently ignored.
     */
    public function __construct($name, $value = false, $path = false, $expiry = false, $is_secure = false)
    {
        $this->host = false;
        $this->name = $name;
        $this->value = $value;
        $this->path = ($path ? $this->fixPath($path) : "/");
        $this->expiry = false;
        if (is_string($expiry)) {
            $this->expiry = strtotime($expiry);
        } elseif (is_integer($expiry)) {
            $this->expiry = $expiry;
        }
        $this->is_secure = $is_secure;
    }

    /**
     *    Sets the host. The cookie rules determine
     *    that the first two parts are taken for
     *    certain TLDs and three for others. If the
     *    new host does not match these rules then the
     *    call will fail.
     *    @param string $host       New hostname.
     *    @return boolean           True if hostname is valid.
     *    @access public
     */
    public function setHost($host)
    {
        if ($host = $this->truncateHost($host)) {
            $this->host = $host;
            return true;
        }
        return false;
    }

    /**
     *    Accessor for the truncated host to which this
     *    cookie applies.
     *    @return string       Truncated hostname.
     *    @access public
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     *    Test for a cookie being valid for a host name.
     *    @param string $host    Host to test against.
     *    @return boolean        True if the cookie would be valid
     *                           here.
     */
    public function isValidHost($host)
    {
        return ($this->truncateHost($host) === $this->getHost());
    }

    /**
     *    Extracts just the domain part that determines a
     *    cookie's host validity.
     *    @param string $host    Host name to truncate.
     *    @return string        Domain or false on a bad host.
     *    @access private
     */
    protected function truncateHost($host)
    {
        $tlds = SimpleUrl::getAllTopLevelDomains();
        if (preg_match('/[a-z\-]+\.(' . $tlds . ')$/i', $host, $matches)) {
            return $matches[0];
        } elseif (preg_match('/[a-z\-]+\.[a-z\-]+\.[a-z\-]+$/i', $host, $matches)) {
            return $matches[0];
        }
        return false;
    }

    /**
     *    Accessor for name.
     *    @return string       Cookie key.
     *    @access public
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *    Accessor for value. A deleted cookie will
     *    have an empty string for this.
     *    @return string       Cookie value.
     *    @access public
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *    Accessor for path.
     *    @return string       Valid cookie path.
     *    @access public
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     *    Tests a path to see if the cookie applies
     *    there. The test path must be longer or
     *    equal to the cookie path.
     *    @param string $path       Path to test against.
     *    @return boolean           True if cookie valid here.
     *    @access public
     */
    public function isValidPath($path)
    {
        return (strncmp(
                $this->fixPath($path),
                $this->getPath(),
                strlen($this->getPath())) == 0);
    }

    /**
     *    Accessor for expiry.
     *    @return string       Expiry string.
     *    @access public
     */
    public function getExpiry()
    {
        if (! $this->expiry) {
            return false;
        }
        return gmdate("D, d M Y H:i:s", $this->expiry) . " GMT";
    }

    /**
     *    Test to see if cookie is expired against
     *    the cookie format time or timestamp.
     *    Will give true for a session cookie.
     *    @param integer/string $now  Time to test against. Result
     *                                will be false if this time
     *                                is later than the cookie expiry.
     *                                Can be either a timestamp integer
     *                                or a cookie format date.
     *    @access public
     */
    public function isExpired($now)
    {
        if (! $this->expiry) {
            return true;
        }
        if (is_string($now)) {
            $now = strtotime($now);
        }
        return ($this->expiry < $now);
    }

    /**
     *    Ages the cookie by the specified number of
     *    seconds.
     *    @param integer $interval   In seconds.
     *    @public
     */
    public function agePrematurely($interval)
    {
        if ($this->expiry) {
            $this->expiry -= $interval;
        }
    }

    /**
     *    Accessor for the secure flag.
     *    @return boolean       True if cookie needs SSL.
     *    @access public
     */
    public function isSecure()
    {
        return $this->is_secure;
    }

    /**
     *    Adds a trailing and leading slash to the path
     *    if missing.
     *    @param string $path            Path to fix.
     *    @access private
     */
    protected function fixPath($path)
    {
        if (substr($path, 0, 1) != '/') {
            $path = '/' . $path;
        }
        if (substr($path, -1, 1) != '/') {
            $path .= '/';
        }
        return $path;
    }
}
