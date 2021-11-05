<?php

namespace Peimengc\Douyin;

use Throwable;

class ResponseContentsException extends \Exception
{
    protected $contents;

    public function __construct($message = "", $contents = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->contents = $contents;
    }

    public function getContents()
    {
        return $this->contents;
    }
}