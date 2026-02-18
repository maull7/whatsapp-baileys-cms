<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\MessageTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestClientUserSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::query()->firstOrCreate(
            ['name' => 'PT Test Indonesia'],
            [
                'name' => 'PT Test Indonesia',
                'api_key' => 'test_api_key_'.bin2hex(random_bytes(16)),
            ]
        );

        $user = User::query()->updateOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Test Client',
                'email' => 'client@example.com',
                'password' => Hash::make('password'),
                'role' => 'client',
                'client_id' => $client->id,
            ]
        );

        MessageTemplate::query()->firstOrCreate(
            [
                'client_id' => $client->id,
                'name' => 'Template Welcome',
            ],
            [
                'client_id' => $client->id,
                'name' => 'Template Welcome',
                'body' => 'Halo {{nama}}, selamat datang! Nomor Anda: {{phone}}',
            ]
        );
    }
}
