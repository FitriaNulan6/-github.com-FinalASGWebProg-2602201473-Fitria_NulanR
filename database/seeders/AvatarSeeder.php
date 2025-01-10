// database/seeders/AvatarSeeder.php
namespace Database\Seeders;

use App\Models\Avatar;
use Illuminate\Database\Seeder;

class AvatarSeeder extends Seeder
{
    public function run()
    {
        $avatars = [
            ['name' => 'Basic Avatar', 'price' => 50, 'image_path' => '/images/avatars/basic.png'],
            ['name' => 'Premium Avatar', 'price' => 100, 'image_path' => '/images/avatars/premium.png'],
            ['name' => 'Gold Avatar', 'price' => 500, 'image_path' => '/images/avatars/gold.png'],
            ['name' => 'Diamond Avatar', 'price' => 1000, 'image_path' => '/images/avatars/diamond.png'],
        ];

        foreach ($avatars as $avatar) {
            Avatar::create($avatar);
        }
    }
}