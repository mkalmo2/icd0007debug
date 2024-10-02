<?php

/**
 *    Collection of header lines in the response.
 *    @package SimpleTest
 *    @subpackage WebTester
 */
class SimpleHttpHeaders
{
    private $raw_headers;
    private $response_code;
    private $http_version;
    private $mime_type;
    private $location;
    private $cookies;
    private $authentication;
    private $realm;

    /**
     *    Parses the incoming header block.
     *    @param string $headers     Header block.
     *    @access public
     */
    public function __construct($headers)
    {
        $this->raw_headers = $headers;
        $this->response_code = false;
        $this->http_version = false;
        $this->mime_type = '';
        $this->location = false;
        $this->cookies = array();
        $this->authentication = false;
        $this->realm = false;
        foreach (explode("\r\n", $headers) as $header_line) {
            $this->parseHeaderLine($header_line);
        }
    }

    /**
     *    Accessor for parsed HTTP protocol version.
     *    @return integer           HTTP error code.
     *    @access public
     */
    public function getHttpVersion()
    {
        return $this->http_version;
    }

    /**
     *    Accessor for raw header block.
     *    @return string        All headers as raw string.
     *    @access public
     */
    public function getRaw()
    {
        return $this->raw_headers;
    }

    /**
     *    Accessor for parsed HTTP error code.
     *    @return integer           HTTP error code.
     *    @access public
     */
    public function getResponseCode()
    {
        return (integer)$this->response_code;
    }

    /**
     *    Returns the redirected URL or false if
     *    no redirection.
     *    @return string      URL or false for none.
     *    @access public
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     *    Test to see if the response is a valid redirect.
     *    @return boolean       True if valid redirect.
     *    @access public
     */
    public function isRedirect()
    {
        return in_array($this->response_code, array(301, 302, 303, 307)) &&
            (boolean)$this->getLocation();
    }

    /**
     *    Test to see if the response is an authentication
     *    challenge.
     *    @return boolean       True if challenge.
     *    @access public
     */
    public function isChallenge()
    {
        return ($this->response_code == 401) &&
            (boolean)$this->authentication &&
            (boolean)$this->realm;
    }

    /**
     *    Accessor for MIME type header information.
     *    @return string           MIME type.
     *    @access public
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     *    Accessor for authentication type.
     *    @return string        Type.
     *    @access public
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     *    Accessor for security realm.
     *    @return string        Realm.
     *    @access public
     */
    public function getRealm()
    {
        return $this->realm;
    }

    /**
     *    Writes new cookies to the cookie jar.
     *    @param SimpleCookieJar $jar   Jar to write to.
     *    @param SimpleUrl $url         Host and path to write under.
     *    @access public
     */
    public function writeCookiesToJar($jar, $url)
    {
        foreach ($this->cookies as $cookie) {
            $jar->setCookie(
                $cookie->getName(),
                $cookie->getValue(),
                $url->getHost(),
                $cookie->getPath(),
                $cookie->getExpiry());
        }
    }

    /**
     *    Called on each header line to accumulate the held
     *    data within the class.
     *    @param string $header_line        One line of header.
     *    @access protected
     */
    protected function parseHeaderLine($header_line)
    {
        if (preg_match('/HTTP\/(\d+\.\d+)\s+(\d+)/i', $header_line, $matches)) {
            $this->http_version = $matches[1];
            $this->response_code = $matches[2];
        }
        if (preg_match('/Content-type:\s*(.*)/i', $header_line, $matches)) {
            $this->mime_type = trim($matches[1]);
        }
        if (preg_match('/Location:\s*(.*)/i', $header_line, $matches)) {
            $this->location = trim($matches[1]);
        }
        if (preg_match('/Set-cookie:(.*)/i', $header_line, $matches)) {
            $this->cookies[] = $this->parseCookie($matches[1]);
        }
        if (preg_match('/WWW-Authenticate:\s+(\S+)\s+realm=\"(.*?)\"/i', $header_line, $matches)) {
            $this->authentication = $matches[1];
            $this->realm = trim($matches[2]);
        }
    }

    /**
     *    Parse the Set-cookie content.
     *    @param string $cookie_line    Text after "Set-cookie:"
     *    @return SimpleCookie          New cookie object.
     *    @access private
     */
    protected function parseCookie($cookie_line)
    {
        $parts = explode(";", $cookie_line);
        $cookie = array();
        preg_match('/\s*(.*?)\s*=(.*)/', array_shift($parts), $cookie);
        foreach ($parts as $part) {
            if (preg_match('/\s*(.*?)\s*=(.*)/', $part, $matches)) {
                $cookie[$matches[1]] = trim($matches[2]);
            }
        }
        return new SimpleCookie(
            $cookie[1],
            trim($cookie[2]),
            isset($cookie["path"]) ? $cookie["path"] : "",
            isset($cookie["expires"]) ? $cookie["expires"] : false);
    }
}
