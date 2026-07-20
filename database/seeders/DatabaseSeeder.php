<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            FormationSeeder::class,
            CourseSeeder::class,
            ConferenceSeeder::class,
            MasterclassSeeder::class,
            CoachingSeeder::class,
            TransactionSeeder::class,
            LibrarySeeder::class,
            ReservationSeeder::class,
            TicketSeeder::class,
            SubscriptionSeeder::class,
            InvoiceSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            SponsorSeeder::class,
            TestimonialSeeder::class,
            TeamMemberSeeder::class,
            NewsletterCampaignSeeder::class,
            SmsCampaignSeeder::class,
            AdminNotificationSeeder::class,
            ContentSeeder::class,
            GallerySeeder::class,
            VideoSeeder::class,
            PagesSeeder::class,
            CustomPageSeeder::class,
        ]);
    }
}
