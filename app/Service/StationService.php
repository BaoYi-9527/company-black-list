<?php

namespace App\Service;

use App\Model\Station;
use Han\Utils\Service;

class StationService extends Service
{

    public function getOrCreateStation($name)
    {
        $station = Station::query()->where('name', $name)->first();
        if ($station) return $station;
        return Station::query()->create([
            'name'   => $name,
        ]);
    }
}