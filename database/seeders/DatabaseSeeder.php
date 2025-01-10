<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            HobbySeeder::class,
            UserSeeder::class,
            AvatarSeeder::class,
        ]);
    }
}





