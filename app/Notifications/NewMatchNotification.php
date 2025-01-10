// app/Notifications/NewMatchNotification.php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewMatchNotification extends Notification
{
    protected $matchedUser;

    public function __construct($matchedUser)
    {
        $this->matchedUser = $matchedUser;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "You matched with {$this->matchedUser->name}!",
            'user_id' => $this->matchedUser->id
        ];
    }
}