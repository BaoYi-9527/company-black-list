<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Model\Company;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class CompanyController
{
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
