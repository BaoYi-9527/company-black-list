<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Exception\BusinessException;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class LoginController
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $response->raw('Hello Hyperf!');
    }
}