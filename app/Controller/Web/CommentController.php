<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Model\Comment;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation as OA;

#[OA\HyperfServer('http')]
class CommentController
{
    public function list(RequestInterface $request, ResponseInterface $response)
    {
        $page      = $request->input('page', 1);
        $pageSize  = $request->input('page_size', 10);
        $companyId = $request->input('company_id', 0);
        $postId    = $request->input('post_id', 0);

        $list = Comment::query()
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->when($postId, function ($query) use ($postId) {
                $query->where('post_id', $postId);
            })
            ->orderByDesc('created_at')
            ->forPage($page)->paginate($pageSize);

        return $response->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => $list,
        ]);
    }
}
