<?php

namespace stagify\Flash;

class Flash
{
    public FlashStatus $status = FlashStatus::success;
    public string $message = "";
    public array $errors = [];

    public function setStatus(FlashStatus $status): Flash
    {
        $this->status = $status;
        return $this;
    }

    public function setMessage(string $message): Flash
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