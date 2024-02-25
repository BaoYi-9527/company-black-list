<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Controller\AbstractController;
use App\Model\Post;
use App\Service\CompanyService;
use App\Service\StationService;
use App\Service\UserService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation as OA;

#[OA\HyperfServer('http')]
class PostController extends AbstractController
{

    #[OA\Get(path: '/post/list', summary: '帖子列表', tags: ['v1/'])]
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
                    new OA\Property(property: 'data', description: '数据', items: new OA\Items(properties: [
                        new OA\Property(property: 'id', description: 'id', type: 'integer'),
                        new OA\Property(property: 'user_id', description: '用户Id', type: 'integer'),
                        new OA\Property(property: 'company_id', description: '公司Id', type: 'integer'),
                        new OA\Property(property: 'station_id', description: '职位Id', type: 'integer'),
                        new OA\Property(property: 'type', description: '帖子类型', type: 'integer'),
                        new OA\Property(property: 'content', description: '内容', type: 'string'),
                        new OA\Property(property: 'ip', description: 'IP地址(数据创建时的)', type: 'string'),
                        new OA\Property(property: 'show', description: '是否展示 0-否 1-是', type: 'integer'),
                        new OA\Property(property: 'created_at', description: '创建时间', type: 'string'),
                        new OA\Property(property: 'updated_at', description: '更新时间', type: 'string'),
                        new OA\Property(property: 'company', description: '帖子所属公司', properties: [
                            new OA\Property(property: 'id', description: 'id', type: 'integer'),
                            new OA\Property(property: 'name', description: '公司名', type: 'string'),
                            new OA\Property(property: 'station', description: '职位(已弃用)', type: 'string'),
                            new OA\Property(property: 'city', description: '所属城市', type: 'string'),
                        ], type: 'object'),
                        new OA\Property(property: 'station', description: '帖子所属职位(帖子发布时用户填写的职位)', properties: [
                            new OA\Property(property: 'id', description: 'id', type: 'integer'),
                            new OA\Property(property: 'name', description: '职位名', type: 'string'),
                        ], type: 'object'),
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
        $stationId = $request->input('station_id', 0);


        $list = Post::with(['company:id,name,station,city', 'station:id,name'])
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->when($stationId, function ($query) use ($stationId) {
                $query->where('station_id', $stationId);
            })
            ->where('show', 1)
            ->orderByDesc('created_at')
            ->forPage($page)->paginate($pageSize);

        return $response->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => $list,
        ]);
    }

    #[OA\Post(path: '/post/add', summary: '发布帖子', tags: ['v1/'])]
    #[OA\RequestBody(
        description: '请求参数',
        content: [
            new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: ['company_name', 'station', 'post_content'],
                    properties: [
                        new OA\Property(property: 'company_name', description: '公司名', type: 'string'),
                        new OA\Property(property: 'city', description: '所在城市', type: 'string'),
                        new OA\Property(property: 'station', description: '职位', type: 'string'),
                        new OA\Property(property: 'post_content', description: '帖子内容', type: 'string'),
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
        $companyName = $request->input('company_name');
        $city        = $request->input('city', '');
        $stationName = $request->input('station');
        $postContent = $request->input('post_content');
        $ip          = $request->getServerParams()['remote_addr'];

        $userService    = $this->container->get(UserService::class);
        $companyService = $this->container->get(CompanyService::class);
        $stationService = $this->container->get(StationService::class);
        $currentUser    = $userService->getCurrentUser($request);
        $currentUserId  = $currentUser['id'];
        $company        = $companyService->getOrCreateCompany($companyName, $city, [
            'ip'      => $ip,
            'station' => $stationName,
        ]);
        $station        = $stationService->getOrCreateStation($stationName);

        Post::create([
            'user_id'    => $currentUserId,
            'station_id' => $station->id,
            'company_id' => $company->id,
            'content'    => $postContent,
            'ip'         => $ip,
        ]);

        return $response->json([
            'code'    => 0,
            'message' => 'success',
            'data'    => [],
        ]);
    }
}
