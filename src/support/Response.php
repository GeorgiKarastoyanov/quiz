<?php

namespace src\support;

class Response
{
    private static $instance;

    /**
     * @var mixed
     */
    private $content;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * @var string
     */
    private $charset = 'utf-8';

    /**
     * @var array
     */
    private $status = [
        'message' => 'OK',
        'code' => 200,
    ];

    /**
     * Response constructor.
     */
    private function __construct()
    {}

    /**
     * @return Response
     */
    public static function getInstance(): Response
    {
        if (! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @param string $contentType
     * @return void
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * @param int $code
     * @return void
     */
    public function setStatusCode(int $code): void
    {
        $this->status['code'] = $code;
    }

    /**
     * @param string $message
     * @return void
     */
    public function setStatusMessage(string $message): void
    {
        $this->status['message'] = $message;
    }

    /**
     * Send response headers
     *
     * @return void
     */
    public function sendHeaders(): void
    {
        foreach ($this->headers as $header) {
            header($header);
        }

        header("HTTP/1.1 {$this->status['code']}: {$this->status['message']}", true, $this->status['code']);

        header('Content-type: ' . $this->contentType . '; charset=' . $this->charset);
    }

    /**
     * @param string $header
     * @return void
     */
    public function setHeader(string $header): void
    {
        $this->headers[] = $header;
    }

    /**
     * Send the response to the client
     */
    public function send()
    {
        $this->sendHeaders();

        echo $this->content;
        die;
    }

    /**
     * @param string $target
     * @param string $action
     */
    public function redirect(string $target, string $action)
    {
        header('Location: index.php?target=' . $target . '&action=' . $action);
        die;
    }
}
