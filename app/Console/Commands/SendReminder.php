<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Notifications\Reminder as NotificationsReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminders To Users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ids = [];
        try {
            
            Reminder::where('reminders.status', 'OPEN')
            ->where('reminders.date',date('Y-m-d H:i'))
            ->join('users','users.id','=','reminders.user_id')
            ->select('reminders.*','users.email','users.name')->lazy()
            ->each( function( $reminder ) use(&$ids) {
                Notification::route('mail', $reminder->email)->notify(new NotificationsReminder( $reminder->toArray() ));
                $ids[] = $reminder->id;
            });
    
            Reminder::whereIn('id',$ids)->update(['status' => 'CLOSED']);

            $this->info("Done");

        } catch ( \Exception $e ) {
            \Log::error("Exception in ". __CLASS__, ['exception' => $e]);
        }    
    }
}
