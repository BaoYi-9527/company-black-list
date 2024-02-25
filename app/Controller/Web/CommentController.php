<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Controller\AbstractController;
use App\Model\Comment;
use App\Model\Post;
use App\Service\UserService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation as OA;

#[OA\HyperfServer('http')]
class CommentController extends AbstractController
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

    public function add(RequestInterface $request, ResponseInterface $response)
    {
        $postId         = $request->input('post_id');
        $commentId      = $request->input('comment_id', 0);
        $commentContent = $request->input('comment');
        $ip             = $request->getServerParams()['remote_addr'];

        $userService = $this->container->get(UserService::class);

        $currentUser   = $userService->getCurrentUser($request);
        $currentUserId = $currentUser['id'];

        $post = Post::find($postId);

        Comment::create([
            'user_id'    => $currentUserId,
            'company_id' => $post->company_id,
            'post_id'    => $postId,
            'parent_id'  => $commentId,
            'comment'    => $commentContent,
            'ip'         => $ip
        ]);

        return $response->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => [],
        ]);
    }
}
