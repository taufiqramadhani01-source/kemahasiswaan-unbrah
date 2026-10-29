<?php

namespace Tests\Feature;

use App\Models\User;
use App\Livewire\Admin\ManageHomepage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HomepageLivewireTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_update_wr3_name(): void
    {
        $this->seed();
        $admin = User::where('role', 'superadmin')->first();

        Livewire::actingAs($admin)
            ->test(ManageHomepage::class)
            ->set('wr3_name', 'Dr. Testing Nama WR3')
            ->assertSet('wr3_name', 'Dr. Testing Nama WR3');
    }
}
