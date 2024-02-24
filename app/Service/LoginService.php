<?php

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use Han\Utils\Service;
use Hyperf\Contract\SessionInterface;
use Hyperf\Redis\Redis;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class LoginService extends Service
{

    /**
     * 注册验证码
     * @param $email
     * @return int
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCaptcha($email)
    {
        $this->emailHasRegistered($email);

        $redis   = $this->container->get(Redis::class);
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

    public function login($email, $password, $request)
    {
        $user = User::query()->where('email', $email)->first();
        if (!$user) throw new BusinessException(ErrorCode::AUTH_EMAIL_NOT_FOUND);

        if (md5($password) != $user->password) throw new BusinessException(ErrorCode::AUTH_PASSWORD_ERROR);

        $user->token           = md5($email . time());
        $user->ip              = $request->getServerParams()['remote_addr'];
        $user->last_login_time = date('Y-m-d H:i:s');
        $user->login_times     = $user->login_times + 1;
        $user->save();
        $this->container->get(SessionInterface::class)->set('user', $user);

        # TODO::Ranger 登录记录

        return $user;
    }

    public function register($email, $password, $captcha, $request)
    {
        $redis   = $this->container->get(Redis::class);
        # 检查 captcha 是否合法
        $key     = 'captcha:' . $email;
        $cacheCaptcha = $redis->get($key);
        if (!$cacheCaptcha || $cacheCaptcha != $captcha) throw new BusinessException(ErrorCode::AUTH_CAPTCHA_ERROR);
        $redis->del($key);

        # 检测邮箱是否已经注册
        $this->emailHasRegistered($email);

        $tempName = $this->generateRandomName() . '-' . $captcha;

        # TODO::Ranger 后续 token 要使用类似 JWT 的组件进行维护

        # 注册
        $token = md5($email . time());
        $user = User::create([
            'name'            => $tempName,
            'email'           => $email,
            'password'        => md5($password),
            'ip'              => $request->getServerParams()['remote_addr'],
            'token'           => $token,
            'captcha'         => $captcha,
            'last_login_time' => date('Y-m-d H:i:s'),
            'login_times'     => 1
        ]);


        # session token
        $session = $this->container->get(SessionInterface::class);
        $session->set($token, $user);

        return $user;
    }

    protected function generateRandomName(): string
    {
        $names = [
            "愚者","魔术师","女祭司","女皇","皇帝","教皇","恋人","战车","力量","隐者","命运之轮",
            "正义","倒吊人","死神","节制","恶魔","塔","星星","月亮","太阳","审判","世界"
        ];

        return $names[array_rand($names)];
    }

    /**
     *
     * @param $email
     * @return void
     */
    protected function emailHasRegistered($email): void
    {
        $exists = User::query()->where('email', $email)->count();
        if ($exists) throw new BusinessException(ErrorCode::AUTH_EMAIL_REGISTERED);
    }
}