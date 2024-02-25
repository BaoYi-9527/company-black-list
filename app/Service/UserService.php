<?php

namespace App\Service;

use Han\Utils\Service;
use Hyperf\Contract\SessionInterface;

class UserService extends Service
{
    public function getCurrentUser($request)
    {
        $headers = $request->getHeaders();
        $auth    = $headers['authorization'];
        $token   = str_replace('Bearer ', '', $auth[0]);
        $session = $this->container->get(SessionInterface::class);
        return $session->get($token);
    }
}