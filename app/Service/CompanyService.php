<?php

namespace App\Service;

use App\Model\Company;
use Han\Utils\Service;

class CompanyService extends Service
{
    public function getOrCreateCompany($name, $city, $ext = [])
    {
        $company = Company::query()->where('name', $name)->first();
        if ($company) return $company;
        return Company::query()->create([
            'name'   => $name,
            'station' => $ext['station'] ?? '',
            'city'   => $city,
            'ip'     => $ext['ip'] ?? '',
            'show'   => $ext['show'] ?? 1,
        ]);
    }
}