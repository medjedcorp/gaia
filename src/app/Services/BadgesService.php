<?php

namespace App\Services;
use App\Models\User;

class BadgesService
{
  public function approval()
  {
    $approval = User::where('accepted' , '0')->count();
    return $approval;
  }
}
