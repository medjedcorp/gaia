<?php

namespace App\Services;

class UserAgentService
{
  public function GetAgent($request)
  {
    $user_agent =  $request->header('User-Agent');
    if ((strpos($user_agent, 'iPhone') !== false)
        || (strpos($user_agent, 'iPod') !== false)
        || (strpos($user_agent, 'Android') !== false)
    ) {
        $terminal = 'mobile';
    } else {
        $terminal = 'pc';
    }

    return $terminal;
  }
}
