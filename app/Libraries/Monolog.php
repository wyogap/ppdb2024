<?php

namespace App\Libraries;

use Monolog\Logger;
use Monolog\Level;
use Monolog\Handler\StreamHandler;
use Psr\Log\AbstractLogger;
use DateTimeZone;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Monolog extends AbstractLogger
{
    protected $logger;

    public function __construct()
    {
        $this->logger = static::getLogger();
    }

    public function log($level, $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }

    public function debug($message, array $context = []): void
    {
        $this->logger->log(Level::Debug, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->logger->log(Level::Info, $message, $context);
    }

    public function warning($message, array $context = []): void
    {
        $this->logger->log(Level::Warning, $message, $context);
    }

    public function error($message, array $context = []): void
    {
        $this->logger->log(Level::Error, $message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $this->logger->log(Level::Critical, $message, $context);
    }

    public function emergency($message, array $context = []): void
    {
        $this->logger->log(Level::Emergency, $message, $context);
    }

    public function addLogHandler($handler) {
        $this->logger->pushHandler($handler);
    }

    public function addStdoutHandler() {
        $this->logger->pushHandler(new StreamHandler('php://stdout', Level::Info));
    }
    
    public static function getLogger() {
        $timezone = new DateTimeZone(APP_TIMEZONE);

        // Pass the timezone object in the Logger constructor
        $logger = new Logger('app', [], [], $timezone);

        // Create the RotatingFileHandler, specifying the log file path
        // The handler automatically appends the date to the filename (e.g., app-2026-02-11.log)
        $handler = new RotatingFileHandler(WRITEPATH . 'logs/' .APP_SHORT_NAME. '.log', Level::Debug);

        // Optional: Customize the log format
        $formatter = new LineFormatter(
            "[%datetime%] %channel%.%level_name%: %context% %message% %extra%\n",
            null,           // Date format (default)
            true,          // Allow inline line breaks (false by default)
            true            // Ignore empty context and extra (true to suppress empty output)
        );
        $handler->setFormatter($formatter); 

        // Push the handler to the logger
        $logger->pushHandler($handler);

        if (!isset($_SERVER['HTTP_HOST'])) 
            $logger->pushHandler(new StreamHandler('php://stdout', Level::Debug));

        return $logger;
    }    
}