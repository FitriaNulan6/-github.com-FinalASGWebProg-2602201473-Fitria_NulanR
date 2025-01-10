// database/seeders/UserSeeder.php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Hobby;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create test users
        $users = [];
        for ($i = 1; $i <= 20; $i++) {
            $gender = $i % 2 === 0 ? 'male' : 'female';
            $users[] = User::create([
                'name' => fake()->name($gender),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'gender' => $gender,
                'mobile_number' => fake()->numerify('08##########'),
                'instagram' => 'http://www.instagram.com/' . fake()->userName(),
                'coins' => 100,
                'is_visible' => true,
            ]);
        }

        // Assign random hobbies to users
        $hobbies = Hobby::all();
        foreach ($users as $user) {
            $randomHobbies = $hobbies->random(rand(3, 5));
            $user->hobbies()->attach($randomHobbies);
        }
    }
}