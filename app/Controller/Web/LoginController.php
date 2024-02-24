<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Controller\AbstractController;
use App\Service\LoginService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class LoginController extends AbstractController
{

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }

    public function sendCaptcha(RequestInterface $request, ResponseInterface $response)
    {
        $params = $request->all();
        $email  = $params['email'];

        $loginService = $this->container->get(LoginService::class);
        $loginService->getCaptcha($email);

        return $response->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => [],
        ]);
    }

    public function register(RequestInterface $request, ResponseInterface $response)
    {
        $params = $request->all();

        $email    = $params['email'];
        $password = $params['password'];
        $captcha  = $params['captcha'];


        return $response->json($params);
    }
}
