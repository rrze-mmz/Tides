<?php

namespace App\Livewire;

use Debugbar;
use Livewire\Component;

class DarkModeSwitcher extends Component
{
    public $darkMode = false;

    public function toggleDarkMode()
    {
        $this->darkMode = ! $this->darkMode;
        session(['darkMode' => $this->darkMode]);
    }

    public function render()
    {
        Debugbar::info($this->darkMode);

        return view('livewire.dark-mode-switcher');
    }
}
