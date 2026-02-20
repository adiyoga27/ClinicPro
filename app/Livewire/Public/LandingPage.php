<?php

namespace App\Livewire\Public;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class LandingPage extends Component
{
    public function render()
    {
        return view('livewire.public.landing-page');
    }
}
