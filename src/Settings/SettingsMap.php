<?php

namespace stagify\Settings;

class SettingsMap implements SettingsInterface
{
    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function get(string $key = "")
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }
}