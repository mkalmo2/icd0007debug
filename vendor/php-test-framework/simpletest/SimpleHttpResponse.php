<?php

/**
 *    Basic HTTP response.
 *    @package SimpleTest
 *    @subpackage WebTester
 */
class SimpleHttpResponse extends SimpleStickyError
{
    private $url;
    private $encoding;
    private $sent;
    private $content;
    private $headers;

    /**
     *    Constructor. Reads and parses the incoming
     *    content and headers.
     *    @param SimpleSocket $socket   Network connection to fetch
     *                                  response text from.
     *    @param SimpleUrl $url         Resource name.
     *    @param mixed $encoding        Record of content sent.
     *    @access public
     */
    public function __construct($socket, $url, $encoding)
    {
        parent::__construct();
        $this->url = $url;
        $this->encoding = $encoding;
        $this->sent = $socket->getSent();
        $this->content = false;
        $raw = $this->readAll($socket);
        if ($socket->isError()) {
            $this->setError('Error reading data from server [' . $socket->getError() . ']');
            $errorCode = $socket->getErrorCode() ?: ERROR_N01;
            $this->setErrorCode($errorCode);
            return;
        }
        $this->parse($raw);
    }

    /**
     *    Splits up the headers and the rest of the content.
     *    @param string $raw    Content to parse.
     *    @access private
     */
    protected function parse($raw)
    {
        if (! $raw) {
            $this->setError(sprintf('Timeout %s seconds', REQUEST_TIMEOUT));
            $this->setErrorCode(ERROR_N03);
            $this->headers = new SimpleHttpHeaders('');
        } elseif ('file' == $this->url->getScheme()) {
            $this->headers = new SimpleHttpHeaders('');
            $this->content = $raw;
        } elseif (! strstr($raw, "\r\n\r\n")) {
            $this->setError('Could not split headers from content');
            $this->headers = new SimpleHttpHeaders($raw);
        } else {
            list($headers, $this->content) = explode("\r\n\r\n", $raw, 2);
            $this->headers = new SimpleHttpHeaders($headers);
        }
    }

    /**
     *    Original request method.
     *    @return string        GET, POST or HEAD.
     *    @access public
     */
    public function getMethod()
    {
        return $this->encoding->getMethod();
    }

    /**
     *    Resource name.
     *    @return SimpleUrl        Current url.
     *    @access public
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *    Original request data.
     *    @return mixed              Sent content.
     *    @access public
     */
    public function getRequestData()
    {
        return $this->encoding;
    }

    /**
     *    Raw request that was sent down the wire.
     *    @return string        Bytes actually sent.
     *    @access public
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     *    Accessor for the content after the last
     *    header line.
     *    @return string           All content.
     *    @access public
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     *    Accessor for header block. The response is the
     *    combination of this and the content.
     *    @return SimpleHttpHeaders        Wrapped header block.
     *    @access public
     */
    public function getHeaders() : ?SimpleHttpHeaders
    {
        return $this->headers;
    }

    /**
     *    Accessor for any new cookies.
     *    @return array       List of new cookies.
     *    @access public
     */
    public function getNewCookies()
    {
        return $this->headers->getNewCookies();
    }

    /**
     *    Reads the whole of the socket output into a
     *    single string.
     *    @param SimpleSocket $socket  Unread socket.
     *    @return string               Raw output if successful
     *                                 else false.
     *    @access private
     */
    protected function readAll($socket): string {
        return $socket->readAll();
    }

    /**
     *    Test to see if the packet from the socket is the
     *    last one.
     *    @param string $packet    Chunk to interpret.
     *    @return boolean          True if empty or EOF.
     *    @access private
     */
    protected function isLastPacket($packet)
    {
        if (is_string($packet)) {
            return $packet === '';
        }
        return ! $packet;
    }
}

