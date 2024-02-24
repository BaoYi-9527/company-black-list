<?php

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Han\Utils\Service;
use Psr\Container\ContainerInterface;

class LoginService extends Service
{

    public function getCaptcha($email)
    {
        $redis   = $this->container->get(\Hyperf\Redis\Redis::class);
        $key     = 'captcha:' . $email;
        $captcha = $redis->get($key);
        if ($captcha) throw new BusinessException(ErrorCode::AUTH_CAPTCHA_SEND);

        $captcha = rand(10000, 99999);


        # 发送 email 邮件
        $emailService = $this->container->get(EmailService::class);
        $emailService->send($email, $captcha);

        $redis->set($key, $captcha, 60);

        return $captcha;
    }
}