<?php

namespace Database\Seeders;

use App\Models\Social;
use App\Models\SocialEvent;
use App\Models\SocialNetwork;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = [
            'instagram',
            'telegram',
            'iptelefoniya'
        ];

        $events = [
            1 => [
                'comments',
                'live_comments',
                'mentions',
                'message_reactions',
                'message',
                'messaging_handover',
                'messaging_postbacks',
                'messaging_referral',
                'messaging_seen',
                'standby',
                'story_insights',
            ]
        ];

        foreach ($array as $item) {
            SocialNetwork::create([
                'name' =>$item
            ]);
        }

        foreach ($events as $key=>$event) {
            foreach ($event as $item) {
                SocialEvent::create([
                    'social_network_id' =>$key,
                    'event' =>$item,
                ]);
            }
        }
    }
}
