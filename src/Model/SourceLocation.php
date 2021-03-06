<?php

namespace Translation\Extractor\Model;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class SourceLocation
{
    /**
     * Translation key.
     *
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $line;

    /**
     * @var array
     */
    private $context;

    /**
     * @param string $message
     * @param string $path
     * @param int    $line
     * @param array  $context
     */
    public function __construct($message, $path, $line, array $context = [])
    {
        $this->message = $message;
        $this->path = (string) $path;
        $this->line = $line;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }
}
