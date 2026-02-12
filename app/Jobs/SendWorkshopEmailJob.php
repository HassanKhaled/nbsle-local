<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\WorkshopNotificationMail;
use App\Models\WorkReg;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;

class SendWorkshopEmailJob implements ShouldQueue
{
    use Dispatchable , InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $workshop_id;
    public $title;
    public $body;
    public $attachments;

    public function __construct($email, $workshop_id, $title, $body, $attachments = [])
    {
        $this->email = $email;
        $this->workshop_id = $workshop_id;
        $this->title = $title;
        $this->body = $body;
        $this->attachments = $attachments;
    }

    public function handle()
    {
        try {
            Mail::send([], [], function ($message) {
                $message->to($this->email)
                        ->subject($this->title)
                        ->setBody($this->body, 'text/html');

                foreach ($this->attachments as $filePath) {
                    $message->attach(storage_path('app/' . $filePath));
                }
            });
            
            WorkReg::where('email', $this->email)
                   ->where('workshop_id', $this->workshop_id)
                   ->update(['email_sent' => true]);

            MailLog::create([
                'workshop_id'   => $this->workshop_id,       
                'from_email'    => config('mail.from.address'),
                'to_email'      => $this->email,
                'subject'       => "WorkShop ".$this->workshop_id. "email",    
                'status'        => 'success',
                'error_message' => null,
            ]);
        } catch (\Exception $e) {
              MailLog::create([
                'workshop_id'   => $this->workshop_id,       
                'from_email'    => config('mail.from.address'),
                'to_email'      => $this->email, 
                'subject'       => "WorkShop " . $this->workshop_id . " email",    
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);        
        }
    }
}
