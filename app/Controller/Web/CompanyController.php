<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Controller\AbstractController;
use App\Model\Company;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation as OA;

#[OA\HyperfServer('http')]
class CompanyController extends AbstractController
{

    #[OA\Get(path: '/company/list', summary: '公司列表', tags: ['v1/'])]
    #[OA\Parameter(name: 'page', description: '当前页', in: 'query', required: true)]
    #[OA\Parameter(name: 'page_size', description: '每页条数', in: 'query', required: true)]
    #[OA\Parameter(name: 'name', description: '公司名', in: 'query', required: false)]
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
                        new OA\Property(property: 'name', description: '公司名', type: 'string'),
                        new OA\Property(property: 'station', description: '职位(已弃用)', type: 'string'),
                        new OA\Property(property: 'city', description: '所属城市', type: 'string'),
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
        $page     = $request->input('page', 1);
        $pageSize = $request->input('page_size', 10);
        $name     = $request->input('name', '');


        $list = Company::query()
            ->when($name, function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
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
