<?php

declare(strict_types=1);

namespace JTokenizer;

class JMessage
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var int
     */
    private $level;

    public function __construct(string $message, int $level)
    {
        $this->message = $message;
        $this->level = $level;
    }
}
