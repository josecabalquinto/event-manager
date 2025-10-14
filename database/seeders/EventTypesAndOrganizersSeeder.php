<?php

namespace Database\Seeders;

use App\Models\EventType;
use App\Models\EventOrganizer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTypesAndOrganizersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Event Types
        $eventTypes = [
            [
                'name' => 'Seminar',
                'description' => 'Educational seminars and academic discussions',
                'color' => '#3B82F6', // Blue
                'icon' => 'academic-cap',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Training',
                'description' => 'Professional training sessions and skill development programs',
                'color' => '#10B981', // Green
                'icon' => 'cog',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Workshop',
                'description' => 'Hands-on workshops and practical learning sessions',
                'color' => '#F59E0B', // Yellow
                'icon' => 'wrench',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($eventTypes as $eventType) {
            EventType::create($eventType);
        }

        // Create Event Organizers
        $eventOrganizers = [
            [
                'name' => 'Dean',
                'department' => 'College of Information and Computing Technology Education',
                'contact_email' => 'dean@cicte.edu',
                'contact_phone' => '+63-123-456-7890',
                'description' => 'Office of the Dean - CICTE',
                'color' => '#DC2626', // Red
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Program Head',
                'department' => 'Academic Programs Office',
                'contact_email' => 'programhead@cicte.edu',
                'contact_phone' => '+63-123-456-7891',
                'description' => 'Program Head Office - Academic Programs',
                'color' => '#7C3AED', // Purple
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'CICTE Student Council',
                'department' => 'Student Government Organization',
                'contact_email' => 'studentcouncil@cicte.edu',
                'contact_phone' => '+63-123-456-7892',
                'description' => 'CICTE Student Council - Student Affairs',
                'color' => '#EF4444', // Pink/Red
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'CICTE Staff',
                'department' => 'College Administration',
                'contact_email' => 'staff@cicte.edu',
                'contact_phone' => '+63-123-456-7893',
                'description' => 'CICTE Administrative Staff',
                'color' => '#06B6D4', // Cyan
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($eventOrganizers as $eventOrganizer) {
            EventOrganizer::create($eventOrganizer);
        }
    }
}
