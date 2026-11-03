<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_required_data(): void
    {
        $this->seed();

        $this->assertSame(2, User::query()->count());
        $this->assertDatabaseHas('users', ['email' => 'admin@example.com', 'role' => User::ROLE_ADMIN]);
        $this->assertDatabaseHas('users', ['email' => 'staff@example.com', 'role' => User::ROLE_STAFF]);

        $this->assertSame(3, Category::query()->count());
        $this->assertSame(15, Asset::query()->count());
        $this->assertSame(15, Asset::query()->distinct()->count('kode'));
    }
}
