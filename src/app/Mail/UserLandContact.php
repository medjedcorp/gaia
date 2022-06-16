<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserLandContact extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $bukken_num;
    private $value;
    private $other;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $bukken_num, $value, $other)
    {
        $this->name = $name;
        $this->bukken_num = $bukken_num;   
        $this->value = $value;   
        $this->other = $other;   
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sub = "物件へのお問合せありがとうございます [" . config('app.name') . "]";
        return $this->subject($sub)
            ->view('emails.landcontact')
            ->with([
                'name' => $this->name,
                'bukken_num' => $this->bukken_num,
                'value' => $this->value,
                'other' => $this->other
              ]);
    }
}