<?php

namespace src\support;

class MessageHandler 
{
    /**
     * @var array
     */
    private $messages = [];
    
    private static $instance;
    
    private function __construct()
    {}
    
    /**
     * @return MessageHandler
     */
    public static function getInstance(): MessageHandler
    {
        if (! self::$instance) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->messages[] = $message;
    }
    
    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
