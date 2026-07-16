<?php

namespace Database\Seeders;

use App\Models\CoachingSession;
use App\Models\Conference;
use App\Models\Masterclass;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'Admin')->inRandomOrder()->take(6)->get();
        $conference = Conference::first();
        $masterclass = Masterclass::first();
        $coaching = CoachingSession::first();

        if ($users->isEmpty() || ! $conference) {
            return;
        }

        $pairs = [
            [$conference, 'confirmed'],
            [$masterclass, 'confirmed'],
            [$coaching, 'pending'],
            [$conference, 'pending'],
            [$masterclass, 'cancelled'],
        ];

        foreach ($pairs as $i => [$event, $status]) {
            if (! $event || ! isset($users[$i])) {
                continue;
            }

            Reservation::updateOrCreate(
                [
                    'user_id' => $users[$i]->id,
                    'reservable_type' => $event->getMorphClass(),
                    'reservable_id' => $event->id,
                ],
                ['status' => $status]
            );
        }
    }
}
