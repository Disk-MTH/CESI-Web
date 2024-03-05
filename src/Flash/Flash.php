<?php

namespace stagify\Flash;

class Flash
{
    public FlashType $type;
    public FlashStatus $status;
    public string $message;

    public function __construct(string $message, FlashStatus $status = FlashStatus::success, FlashType $type = FlashType::banner)
    {
        $this->message = $message;
        $this->type = $type;
        $this->status = $status;
    }

    public function __toString(): string
    {
        return "Flash: (type: " . $this->type->name . ", status: " . $this->status->name . ", message: " . $this->message . ")";
    }
}