<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CsvSuccessMail extends Mailable
{
  use Queueable, SerializesModels;

  protected $title;
  protected $text;
  protected $upload_filename;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($name, $upload_filename)
  {
    $this->title = sprintf('%s 様', $name);
    $this->name = $name;
    $this->up_fname = $upload_filename;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->view('emails.csv_success')
      ->text('emails.csv_success_plain')
      ->subject("※CSV更新処理、成功のお知らせ[" . config('app.name') . "]")
      ->with([
        'name' => $this->name,
        'up_fname' => $this->up_fname,
      ]);
  }
}
