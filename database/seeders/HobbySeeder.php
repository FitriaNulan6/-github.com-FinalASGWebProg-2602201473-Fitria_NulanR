// database/seeders/HobbySeeder.php
namespace Database\Seeders;

use App\Models\Hobby;
use Illuminate\Database\Seeder;

class HobbySeeder extends Seeder
{
    public function run()
    {
        $hobbies = [
            'Reading', 'Gaming', 'Cooking', 'Photography', 'Traveling',
            'Music', 'Sports', 'Art', 'Dancing', 'Writing',
            'Gardening', 'Yoga', 'Movies', 'Hiking', 'Cycling'
        ];

        foreach ($hobbies as $hobby) {
            Hobby::create(['name' => $hobby]);
        }
    }
}