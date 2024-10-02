<?php

/**
 *    Creates HTTP headers for the end point of
 *    a HTTP request via a proxy server.
 *    @package SimpleTest
 *    @subpackage WebTester
 */
class SimpleProxyRoute extends SimpleRoute
{
    private $proxy;
    private $username;
    private $password;

    /**
     *    Stashes the proxy address.
     *    @param SimpleUrl $url     URL as object.
     *    @param string $proxy      Proxy URL.
     *    @param string $username   Username for autentication.
     *    @param string $password   Password for autentication.
     *    @access public
     */
    public function __construct($url, $proxy, $username = false, $password = false)
    {
        parent::__construct($url);
        $this->proxy = $proxy;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     *    Creates the first line which is the actual request.
     *    @param string $method   HTTP request method, usually GET.
     *    @param SimpleUrl $url   URL as object.
     *    @return string          Request line content.
     *    @access protected
     */
    public function getRequestLine($method)
    {
        $url = $this->getUrl();
        $scheme = $url->getScheme() ? $url->getScheme() : 'http';
        $port = $url->getPort() ? ':' . $url->getPort() : '';
        return $method . ' ' . $scheme . '://' . $url->getHost() . $port .
            $url->getPath() . $url->getEncodedRequest() . ' HTTP/1.0';
    }

    /**
     *    Creates the host part of the request.
     *    @param SimpleUrl $url   URL as object.
     *    @return string          Host line content.
     *    @access protected
     */
    public function getHostLine()
    {
        $host = 'Host: ' . $this->proxy->getHost();
        $port = $this->proxy->getPort() ? $this->proxy->getPort() : 8080;
        return "$host:$port";
    }

    /**
     *    Opens a socket to the route.
     *    @param string $method       HTTP request method, usually GET.
     *    @param integer $timeout     Connection timeout.
     *    @return SimpleSocket        New socket.
     *    @access public
     */
    public function createConnection($method, $timeout)
    {
        $socket = $this->createSocket(
            $this->proxy->getScheme() ? $this->proxy->getScheme() : 'http',
            $this->proxy->getHost(),
            $this->proxy->getPort() ? $this->proxy->getPort() : 8080,
            $timeout);
        if ($socket->isError()) {
            return $socket;
        }
        $socket->write($this->getRequestLine($method) . "\r\n");
        $socket->write($this->getHostLine() . "\r\n");
        if ($this->username && $this->password) {
            $socket->write('Proxy-Authorization: Basic ' .
                base64_encode($this->username . ':' . $this->password) .
                "\r\n");
        }
        $socket->write("Connection: close\r\n");
        return $socket;
    }
}

