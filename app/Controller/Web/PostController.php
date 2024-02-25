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
