<?php

namespace stagify\Flash;

class Flash
{
    public FlashStatus $status;
    public string $message;

    public function __construct(string $message, FlashStatus $status = FlashStatus::success)
    {
        $this->message = $message;
        return $this;
    }

    public function setErrors(array $errors): Flash
    {
        $this->errors = $errors;
        return $this;
    }
}