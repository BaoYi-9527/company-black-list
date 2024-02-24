<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Middleware\AuthMiddleware;
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::addGroup('/v1/', function () {
    Router::post('send-captcha', 'App\Controller\Web\LoginController@sendCaptcha');
    Router::post('register', 'App\Controller\Web\LoginController@register');
    Router::post('login', 'App\Controller\Web\LoginController@login');

    Router::addGroup('', function () {
        # 注销
        Router::post('logout', 'App\Controller\Web\LoginController@logout');
    }, ['middleware' => [AuthMiddleware::class]]);


});