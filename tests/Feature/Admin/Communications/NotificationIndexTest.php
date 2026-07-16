<?php

namespace Tests\Feature\Admin\Communications;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_notifications_index_page_loads(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/admin/communications/notifications');

        $response->assertOk();
    }
}
