<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Controller\AbstractController;
use App\Service\LoginService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation as OA;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[OA\HyperfServer('http')]
class LoginController extends AbstractController
{

    #[OA\Post(path: '/login', description: '发送验证码', tags: ['v1/'])]
    #[OA\RequestBody(
        description: '请求参数',
        content: [
            new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['email', 'password'],
                    properties: [
                        new OA\Property(property: 'email', description: '邮箱', type: 'string'),
                        new OA\Property(property: 'password', description: '密码', type: 'string'),
                    ]
                )
            )
        ]
    )]
    #[OA\Response(response: 200, description: '响应参数', content: new OA\MediaType(
        mediaType: 'application/json',
        schema: new OA\Schema(
            properties: [
                new OA\Property(property: 'code', description: '状态码', type: 'integer'),
                new OA\Property(property: 'message', description: '消息', type: 'string'),
                new OA\Property(property: 'data', description: '数据', properties: [
                    new OA\Property(property: 'name', description: '用户名', type: 'string'),
                    new OA\Property(property: 'email', description: '邮箱', type: 'string'),
                    new OA\Property(property: 'token', description: 'token', type: 'string'),
                ], type: 'object'),
            ]
        )))]
    public function login(RequestInterface $request, ResponseInterface $response)
    {
        $params   = $request->all();
        $email    = $params['email'];
        $password = $params['password'];

        $loginService = $this->container->get(LoginService::class);
        $user         = $loginService->login($email, $password, $request);

        return $response->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => [
                'name'  => $user->name,
                'email' => $user->email,
                'token' => $user->token,
            ],
        ]);
    }

    #[OA\Post(path: '/send-captcha', description: '发送验证码', tags: ['v1/'])]
    #[OA\RequestBody(
        description: '请求参数',
        content: [
            new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['email'],
                    properties: [
                        new OA\Property(property: 'email', description: '邮箱', type: 'string'),
                    ]
                )
            )
        ]
    )]
    #[OA\Response(response: 200, description: '响应参数', content: new OA\MediaType(
        mediaType: 'application/json',
        schema: new OA\Schema(
            properties: [
                new OA\Property(property: 'code', description: '状态码', type: 'integer'),
                new OA\Property(property: 'message', description: '消息', type: 'string'),
                new OA\Property(property: 'data', description: '数据', type: 'object'),
            ]
        )))]
    public function sendCaptcha(RequestInterface $request, ResponseInterface $response): \Psr\Http\Message\ResponseInterface
    {
        $params = $request->all();
        $email  = $params['email'];

        $loginService = $this->container->get(LoginService::class);
        $loginService->getCaptcha($email);

        # TODO::Ranger 返回后续要做封装
        return $response->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => [],
        ]);
    }

    #[OA\Post(path: '/register', description: '发送验证码', tags: ['v1/'])]
    #[OA\RequestBody(
        description: '请求参数',
        content: [
            new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['email', 'password', 'captcha'],
                    properties: [
                        new OA\Property(property: 'email', description: '邮箱', type: 'string'),
                        new OA\Property(property: 'password', description: '密码', type: 'string'),
                        new OA\Property(property: 'captcha', description: '验证码', type: 'string'),
                    ]
                )
            )
        ]
    )]
    #[OA\Response(response: 200, description: '响应参数', content: new OA\MediaType(
        mediaType: 'application/json',
        schema: new OA\Schema(
            properties: [
                new OA\Property(property: 'code', description: '状态码', type: 'integer'),
                new OA\Property(property: 'message', description: '消息', type: 'string'),
                new OA\Property(property: 'data', description: '数据', properties: [
                    new OA\Property(property: 'name', description: '用户名', type: 'string'),
                    new OA\Property(property: 'email', description: '邮箱', type: 'string'),
                    new OA\Property(property: 'token', description: 'token', type: 'string'),
                ], type: 'object'),
            ]
        )))]
    public function register(RequestInterface $request, ResponseInterface $response)
    {
        $params = $request->all();

        $email    = $params['email'];
        $password = $params['password'];
        $captcha  = $params['captcha'];

        $loginService = $this->container->get(LoginService::class);
        $user         = $loginService->register($email, $password, $captcha, $request);

        return $response->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => [
                'name'  => $user->name,
                'email' => $user->email,
                'token' => $user->token,
            ],
        ]);
    }
}
