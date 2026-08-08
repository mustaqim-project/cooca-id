<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoogleOauthConfigSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('api_integrations')->updateOrInsert(
            ['provider' => 'google_oauth'],
            [
                'client_id' => 'YOUR_GOOGLE_CLIENT_ID',
                'client_secret' => 'YOUR_GOOGLE_CLIENT_SECRET',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
