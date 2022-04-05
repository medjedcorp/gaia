<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminLandContact extends Mailable
{
    use Queueable, SerializesModels;

    private $name;
    private $bukken_num;
    private $tel;
    private $contact_mail;
    private $value;
    private $other;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $bukken_num, $tel, $contact_mail, $value, $other)
    {
        $this->name = $name;
        $this->bukken_num = $bukken_num;
        $this->tel = $tel;
        $this->contact_mail = $contact_mail;
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
        $sub = "物件へのお問い合わせがありました [" . config('app.name') . "]";
        return $this->subject($sub)
            ->view('emails.usercontact')
            ->with([
                'name' => $this->name,
                'bukken_num' => $this->bukken_num,
                'tel' => $this->tel,
                'contact_mail' => $this->contact_mail,
                'value' => $this->value,
                'other' => $this->other
              ]);
    }
}