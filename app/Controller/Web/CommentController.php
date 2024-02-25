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

    #[OA\Get(path: '/comment/list', summary: '评论列表', tags: ['v1/'])]
    #[OA\Parameter(name: 'page', description: '当前页', in: 'query', required: true)]
    #[OA\Parameter(name: 'page_size', description: '每页条数', in: 'query', required: true)]
    #[OA\Parameter(name: 'company_id', description: '公司名Id', in: 'query', required: false)]
    #[OA\Parameter(name: 'station_id', description: '职位Id', in: 'query', required: false)]
    #[OA\Response(response: 200, description: '返回列表', content: new OA\MediaType(
        mediaType: 'application/json',
        schema: new OA\Schema(
            properties: [
                new OA\Property(property: 'code', description: '状态码', type: 'integer'),
                new OA\Property(property: 'message', description: '消息', type: 'string'),
                new OA\Property(property: 'data', description: '数据', properties: [
                    new OA\Property(property: 'current_page', description: '当前页', type: 'integer'),
                    new OA\Property(property: 'data', description: '数据', type: 'array', items: new OA\Items(properties: [
                        new OA\Property(property: 'id', description: 'id', type: 'integer'),
                        new OA\Property(property: 'user_id', description: '用户Id', type: 'integer'),
                        new OA\Property(property: 'company', description: '公司ID', type: 'integer'),
                        new OA\Property(property: 'post_id', description: '帖子Id', type: 'integer'),
                        new OA\Property(property: 'parent_id', description: '父级评论ID 为0表示为根评论 不为0表示对父级评论的回复', type: 'integer'),
                        new OA\Property(property: 'comment', description: '内容', type: 'string'),
                        new OA\Property(property: 'ip', description: 'IP地址(数据创建时的)', type: 'string'),
                        new OA\Property(property: 'show', description: '是否展示 0-否 1-是', type: 'integer'),
                        new OA\Property(property: 'created_at', description: '创建时间', type: 'string'),
                        new OA\Property(property: 'updated_at', description: '更新时间', type: 'string'),
                    ], type: 'object')),
                    new OA\Property(property: 'first_page_url', description: '首页链接', type: 'string'),
                    new OA\Property(property: 'from', description: '起始页', type: 'integer'),
                    new OA\Property(property: 'last_page', description: '最后页', type: 'integer'),
                    new OA\Property(property: 'last_page_url', description: '最后页链接', type: 'string'),
                    new OA\Property(property: 'next_page_url', description: '下一页链接', type: 'string'),
                    new OA\Property(property: 'path', description: '路径', type: 'string'),
                    new OA\Property(property: 'per_page', description: '每页条数', type: 'integer'),
                    new OA\Property(property: 'prev_page_url', description: '上一页链接', type: 'string'),
                    new OA\Property(property: 'to', description: '结束页', type: 'integer'),
                    new OA\Property(property: 'total', description: '总条数', type: 'integer'),
                ], type: 'object'),
            ]
        )
    ))]
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

    #[OA\Post(path: '/comment/add', summary: '发布帖子', tags: ['v1/'])]
    #[OA\RequestBody(
        description: '请求参数',
        content: [
            new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['post_id', 'comment'],
                    properties: [
                        new OA\Property(property: 'post_id', description: '帖子ID', type: 'integer'),
                        new OA\Property(property: 'comment', description: '评论内容', type: 'string'),
                        new OA\Property(property: 'comment_id', description: '评论ID', type: 'integer'),
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
