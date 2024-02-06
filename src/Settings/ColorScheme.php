<?php

namespace stagify\Settings;

use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;
use Bramus\Monolog\Formatter\ColorSchemes\ColorSchemeInterface;
use Bramus\Monolog\Formatter\ColorSchemes\ColorSchemeTrait;
use Monolog\Level;

class ColorScheme implements ColorSchemeInterface
{
    use ColorSchemeTrait {
        ColorSchemeTrait::__construct as private __constructTrait;
    }

    public function __construct()
    {
        $this->__constructTrait();

        $this->setColorizeArray(array(
            Level::Debug->value => $this->ansi->color([SGR::COLOR_FG_CYAN])->get(),
            Level::Info->value => $this->ansi->color([SGR::COLOR_FG_GREEN])->get(),
            Level::Notice->value => $this->ansi->color([SGR::COLOR_FG_PURPLE])->get(),
            Level::Warning->value => $this->ansi->color([SGR::COLOR_FG_YELLOW])->get(),
            Level::Error->value => $this->ansi->color([SGR::COLOR_FG_RED])->get(),
            Level::Critical->value => $this->ansi->color([SGR::COLOR_FG_RED])->underline()->get(),
            Level::Alert->value => $this->ansi->color([SGR::COLOR_FG_BLACK, SGR::COLOR_BG_RED])->get(),
            Level::Emergency->value => $this->ansi->color([SGR::COLOR_BG_RED_BRIGHT])->color([SGR::COLOR_FG_BLACK])->bold()->underline()->get(),
        ));
    }
}