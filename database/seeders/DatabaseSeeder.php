<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Bed;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@hostel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_approved' => true,
        ]);

        // Student
        $student = User::create([
            'name' => 'John Doe',
            'email' => 'john@hostel.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'is_approved' => true,
        ]);

        // Rooms and Beds
        $rooms = [
            ['room_number' => '101', 'capacity' => 2],
            ['room_number' => '102', 'capacity' => 3],
        ];

        foreach ($rooms as $roomData) {
            $room = Room::create($roomData);
            for ($i = 1; $i <= $room->capacity; $i++) {
                $bed = Bed::create([
                    'room_id' => $room->id,
                    'bed_number' => $room->room_number . '-' . $i,
                ]);
                
                // Assign first bed to John
                if ($room->room_number == '101' && $i == 1) {
                    $bed->update(['is_occupied' => true]);
                    $student->update(['bed_id' => $bed->id]);
                }
            }
        }
    }
}
