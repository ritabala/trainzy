<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\User;
use App\Models\Gym;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MessagesTableSeeder extends Seeder
{
    public function run(): void
    {
        $gyms = Gym::all();

        foreach ($gyms as $gym) {
            $staff = User::whereHas('roles', function ($q) use ($gym) {
                $q->whereIn('name', ['admin-' . $gym->id, 'staff-' . $gym->id]);
            })->get();        
            $members = User::whereHas('roles', fn ($q) => $q->where('name', 'member-' . $gym->id))->get();

            // Create messages for each recipient type
            $messages = [
                // Message to all staff
                [
                    'recipient_type' => 'staff',
                    'recipient_ids' => null, // null means all staff
                    'subject' => 'Important Announcement for All Staff',
                    'body' => 'This is a message sent to all staff members. Please review the latest updates and procedures.',
                    'recipients' => $staff
                ],
                // Message to all members
                [
                    'recipient_type' => 'members',
                    'recipient_ids' => null, // null means all members
                    'subject' => 'Important Update for All Members',
                    'body' => 'This is a message sent to all members. Please check your account for important updates.',
                    'recipients' => $members
                ],
                // Message to selected staff
                [
                    'recipient_type' => 'staff',
                    'recipient_ids' => $staff->random(min(3, $staff->count()))->pluck('id')->toArray(),
                    'subject' => 'Special Task for Selected Staff',
                    'body' => 'This message is specifically for selected staff members who need to complete special tasks.',
                    'recipients' => $staff->random(min(3, $staff->count()))
                ],
                // Message to selected members
                [
                    'recipient_type' => 'members',
                    'recipient_ids' => $members->random(min(3, $members->count()))->pluck('id')->toArray(),
                    'subject' => 'Special Offer for Selected Members',
                    'body' => 'This message is specifically for selected members who qualify for special offers.',
                    'recipients' => $members->random(min(3, $members->count()))
                ]
            ];

            foreach ($messages as $data) {
                $message = Message::create([
                    'gym_id' => $gym->id,
                    'subject' => $data['subject'],
                    'body' => $data['body'],
                    'recipient_type' => $data['recipient_type'],
                    'recipient_ids' => $data['recipient_ids'],
                    'created_by' => $staff->random()->id,
                ]);

                // Track each recipient
                foreach ($data['recipients'] as $user) {
                    MessageRecipient::create([
                        'message_id' => $message->id,
                        'user_id' => $user->id,
                        'status' => fake()->randomElement(['sent', 'failed']),
                        'sent_at' => now()->subMinutes(rand(1, 60)),
                    ]);
                }
            }
        }
    }
}
