<?php

namespace App\Service;

use EasyWeChat\MiniApp\Application;
use Han\Utils\Service;
use Hyperf\Di\Annotation\Inject;

class LoginService extends Service
{
    #[Inject]
    protected Application $application;

    public function dump()
    {
        dd($this->application);
//        var_dump($this->application::class);
    }
}