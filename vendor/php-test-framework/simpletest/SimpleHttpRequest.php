<?php

/**
 *    HTTP request for a web page. Factory for
 *    HttpResponse object.
 *    @package SimpleTest
 *    @subpackage WebTester
 */
class SimpleHttpRequest
{
    private $route;
    private $encoding;
    private $headers;
    private $cookies;

    /**
     *    Builds the socket request from the different pieces.
     *    These include proxy information, URL, cookies, headers,
     *    request method and choice of encoding.
     *    @param SimpleRoute $route              Request route.
     *    @param SimpleEntityEncoding $encoding    Content to send with
     *                                           request.
     *    @access public
     */
    public function __construct($route, $encoding)
    {
        $this->route = $route;
        $this->encoding = $encoding;
        $this->headers = array();
        $this->cookies = array();
    }

    /**
     *    Dispatches the content to the route's socket.
     *    @param integer $timeout      Connection timeout.
     *    @return SimpleHttpResponse   A response which may only have
     *                                 an error, but hopefully has a
     *                                 complete web page.
     *    @access public
     */
    public function fetch($timeout)
    {
        $socket = $this->route->createConnection($this->encoding->getMethod(), $timeout);
        if (! $socket->isError()) {
            $this->dispatchRequest($socket, $this->encoding);
        }

        $response = $this->createResponse($socket);

        $socket->close();

        return $response;
    }

    /**
     *    Sends the headers.
     *    @param SimpleSocket $socket           Open socket.
     *    @param string $method                 HTTP request method,
     *                                          usually GET.
     *    @param SimpleFormEncoding $encoding   Content to send with request.
     *    @access private
     */
    protected function dispatchRequest($socket, $encoding)
    {
        foreach ($this->headers as $header_line) {
            $socket->write($header_line . "\r\n");
        }
        if (count($this->cookies) > 0) {
            $socket->write("Cookie: " . implode(";", $this->cookies) . "\r\n");
        }
        $encoding->writeHeadersTo($socket);
        $socket->write("\r\n");
        $encoding->writeTo($socket);
    }

    /**
     *    Adds a header line to the request.
     *    @param string $header_line    Text of full header line.
     *    @access public
     */
    public function addHeaderLine($header_line)
    {
        $this->headers[] = $header_line;
    }

    /**
     *    Reads all the relevant cookies from the
     *    cookie jar.
     *    @param SimpleCookieJar $jar     Jar to read
     *    @param SimpleUrl $url           Url to use for scope.
     *    @access public
     */
    public function readCookiesFromJar(SimpleCookieJar $jar, SimpleUrl $url): void {
        $this->cookies = $jar->selectAsPairs($url);
    }

    /**
     *    Wraps the socket in a response parser.
     *    @param SimpleSocket $socket   Responding socket.
     *    @return SimpleHttpResponse    Parsed response object.
     *    @access protected
     */
    protected function createResponse(SimpleSocket $socket): SimpleHttpResponse {
        return new SimpleHttpResponse(
            $socket,
            $this->route->getUrl(),
            $this->encoding);
    }
}

