<?php

namespace App\Livewire;

use App\Livewire\Actions\Logout;
use Livewire\Component;

class ProfileDropdown extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        // Redirect ke halaman landing setelah logout
        $this->redirect('/', navigate: true);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.profile-dropdown');
    }
}
