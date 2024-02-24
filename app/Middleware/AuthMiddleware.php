<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Contract\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $headers = $request->getHeaders();
        $auth    = $headers['authorization'] ?? [];
        if (empty($auth) || empty($auth[0])) {
            throw new BusinessException(ErrorCode::AUTH_FAIL);
        } else {
            $token = str_replace('Bearer ', '', $auth[0]);
            # session token
            $session = $this->container->get(SessionInterface::class);
            $user    = $session->get($token);
            if (!$user) throw new BusinessException(ErrorCode::AUTH_FAIL);
            # 判断 token 时间是否过期
            $lastLoginTime = $user['last_login_time'];
            $diffSeconds   = strtotime(date('Y-m-d H:i:s')) - strtotime($lastLoginTime);
            if ($diffSeconds > 3600 * 2) {
                $session->forget($token);
                throw new BusinessException(ErrorCode::AUTH_FAIL);
            }
        }


        return $handler->handle($request);
    }
}
