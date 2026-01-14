<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hall;
use App\Models\HallImage;

class HallSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Hall::truncate();
        HallImage::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $halls = [
            [
                'name' => 'Executive Boardroom A',
                'location' => 'New York',
                'space_type' => 'conference',
                'capacity' => 20,
                'price_per_hour' => 50.00,
                'description' => 'A premium boardroom with panoramic views, equipped with high-end video conferencing.',
                'image' => '/storage/halls/boardroom.jpg', // Main thumbnail
                'more_images' => ['/storage/halls/boardroom.jpg', '/storage/halls/meeting.jpg', '/storage/halls/coworking.jpg'],
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'available_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
            ],
            [
                'name' => 'Creative Hub Zone',
                'location' => 'San Francisco',
                'space_type' => 'coworking',
                'capacity' => 8,
                'price_per_hour' => 25.00,
                'description' => 'An inspiring open space perfect for brainstorming sessions and small teams.',
                'image' => '/storage/halls/coworking.jpg',
                'more_images' => ['/storage/halls/coworking.jpg', '/storage/halls/training.jpg'],
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'available_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
            ],
            [
                'name' => 'Tech Training Center',
                'location' => 'Chicago',
                'space_type' => 'training',
                'capacity' => 50,
                'price_per_hour' => 80.00,
                'description' => 'Spacious training hall with individual desks, projector, and high-speed internet.',
                'image' => '/storage/halls/training.jpg',
                'more_images' => ['/storage/halls/training.jpg', '/storage/halls/boardroom.jpg'],
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'available_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
            ],
            [
                'name' => 'Skyline Meeting Room',
                'location' => 'Seattle',
                'space_type' => 'meeting',
                'capacity' => 10,
                'price_per_hour' => 40.00,
                'description' => 'Modern meeting room with a city view. Quiet environment suitable for client meetings.',
                'image' => '/storage/halls/meeting.jpg',
                'more_images' => ['/storage/halls/meeting.jpg', '/storage/halls/coworking.jpg'],
                'start_time' => '10:00:00',
                'end_time' => '22:00:00',
                'available_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
            ],
            [
                'name' => 'Strategy Room B',
                'location' => 'New York',
                'space_type' => 'conference',
                'capacity' => 12,
                'price_per_hour' => 45.00,
                'description' => 'Compact boardroom for strategy sessions.',
                'image' => '/storage/halls/boardroom.jpg',
                'more_images' => ['/storage/halls/boardroom.jpg', '/storage/halls/meeting.jpg'],
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'available_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
            ],
            [
                'name' => 'Innovation Lab',
                'location' => 'San Francisco',
                'space_type' => 'coworking',
                'capacity' => 15,
                'price_per_hour' => 30.00,
                'description' => 'Open space with modular furniture.',
                'image' => '/storage/halls/coworking.jpg',
                'more_images' => ['/storage/halls/coworking.jpg', '/storage/halls/training.jpg'],
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'available_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
            ],
            [
                'name' => 'Seminar Hall X',
                'location' => 'Chicago',
                'space_type' => 'training',
                'capacity' => 100,
                'price_per_hour' => 120.00,
                'description' => 'Large hall for conferences and seminars.',
                'image' => '/storage/halls/training.jpg',
                'more_images' => ['/storage/halls/training.jpg'],
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'available_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']
            ],
            [
                'name' => 'Client Suite',
                'location' => 'Seattle',
                'space_type' => 'meeting',
                'capacity' => 6,
                'price_per_hour' => 35.00,
                'description' => 'Private suite for confidential client meetings.',
                'image' => '/storage/halls/meeting.jpg',
                'more_images' => ['/storage/halls/meeting.jpg', '/storage/halls/boardroom.jpg'],
                'start_time' => '10:00:00',
                'end_time' => '19:00:00',
                'available_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
            ]
        ];

        foreach ($halls as $data) {
            $hall = Hall::create([
                'name' => $data['name'],
                'location' => $data['location'],
                'space_type' => $data['space_type'],
                'capacity' => $data['capacity'],
                'price_per_hour' => $data['price_per_hour'],
                'description' => $data['description'],
                'image' => $data['image'], // Main thumbnail
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'available_days' => $data['available_days']
            ]);

            // Associate additional gallery images
            foreach ($data['more_images'] as $img) {
                HallImage::create([
                    'hall_id' => $hall->id,
                    'image_path' => $img
                ]);
            }
        }
    }
}
