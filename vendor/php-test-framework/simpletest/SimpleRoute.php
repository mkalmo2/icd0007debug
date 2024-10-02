<?php

/**
 *    Creates HTTP headers for the end point of
 *    a HTTP request.
 *    @package SimpleTest
 *    @subpackage WebTester
 */
class SimpleRoute
{
    private $url;

    /**
     *    Sets the target URL.
     *    @param SimpleUrl $url   URL as object.
     *    @access public
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     *    Resource name.
     *    @return SimpleUrl        Current url.
     *    @access protected
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *    Creates the first line which is the actual request.
     *    @param string $method   HTTP request method, usually GET.
     *    @return string          Request line content.
     *    @access protected
     */
    protected function getRequestLine($method)
    {
        return $method . ' ' . $this->url->getPath() .
            $this->url->getEncodedRequest() . ' HTTP/1.0';
    }

    /**
     *    Creates the host part of the request.
     *    @return string          Host line content.
     *    @access protected
     */
    protected function getHostLine()
    {
        $line = 'Host: ' . $this->url->getHost();
        if ($this->url->getPort()) {
            $line .= ':' . $this->url->getPort();
        }
        return $line;
    }

    /**
     *    Opens a socket to the route.
     *    @param string $method      HTTP request method, usually GET.
     *    @param integer $timeout    Connection timeout.
     *    @return SimpleSocket       New socket.
     *    @access public
     */
    public function createConnection($method, $timeout)
    {
        $default_port = ('https' == $this->url->getScheme()) ? 443 : 80;
        $socket = $this->createSocket(
            $this->url->getScheme() ? $this->url->getScheme() : 'http',
            $this->url->getHost(),
            $this->url->getPort() ? $this->url->getPort() : $default_port,
            $timeout);
        if (! $socket->isError()) {
            $socket->write($this->getRequestLine($method) . "\r\n");
            $socket->write($this->getHostLine() . "\r\n");
            $socket->write("Connection: close\r\n");
        }
        return $socket;
    }

    /**
     *    Factory for socket.
     *    @param string $scheme                   Protocol to use.
     *    @param string $host                     Hostname to connect to.
     *    @param integer $port                    Remote port.
     *    @param integer $timeout                 Connection timeout.
     *    @return SimpleSocket/SimpleSecureSocket New socket.
     *    @access protected
     */
    protected function createSocket($scheme, $host, $port, $timeout)
    {
        if (in_array($scheme, array('file'))) {
            return new SimpleFileSocket($this->url);
        } elseif (in_array($scheme, array('https'))) {
            return new SimpleSecureSocket($host, $port, $timeout);
        } else {
            return new SimpleSocket($host, $port, $timeout);
        }
    }
}