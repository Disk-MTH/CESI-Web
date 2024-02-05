<?php

namespace stagify\Settings;

interface SettingsInterface
{
    public function get(string $key = "");
}