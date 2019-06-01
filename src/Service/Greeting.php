<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class Greeting
{
    private $logger;
    /**
    * @var string
    */
    private $message;
    public function __construct(LoggerInterface $logger, string $message)
    {
        $this->logger = $logger;
        $this->message=$message;
    }
    public function greet(string $name): string
    {
        $this->logger->info("Greated $name");
        return "Hello '{$this->message}' $name";
    }
}
