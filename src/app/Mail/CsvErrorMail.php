<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CsvErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $title;
    protected $text;
    protected $path;
    protected $upload_filename;

    public function __construct($name, $path, $upload_filename)
    {
      $this->title = sprintf('%s 様', $name);
      $this->path = $path;
      $this->name = $name;
      $this->up_fname = $upload_filename;
    }

    public function build()
    {
      return $this->view('emails.csv_erros')
                  ->text('emails.csv_erros_plain')
                  ->subject("※CSV更新処理エラーのお知らせ[" . config('app.name') . "]")
                  ->with([
                        'downloadLink' => $this->path,
                        'name' => $this->name,
                        'up_fname' => $this->up_fname,
                    ]);
    }
}
