<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Model\Post;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation as OA;

#[OA\HyperfServer('http')]
class PostController
{
    public function list(RequestInterface $request, ResponseInterface $response)
    {
        $page     = $request->input('page', 1);
        $pageSize = $request->input('page_size', 10);


        $list = Post::with(['company'])->orderByDesc('created_at')->forPage($page)->paginate($pageSize);

        return $response->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => $list,
        ]);
    }
}
