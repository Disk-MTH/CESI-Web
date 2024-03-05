<?php

namespace stagify\Flash;

class Flash
{
    public FlashStatus $status;
    public string $message;

    public function __construct(string $message, FlashStatus $status = FlashStatus::success)
    {
        $this->message = $message;
        $this->status = $status;
    }
}