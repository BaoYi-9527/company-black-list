<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $station 
 * @property string $city 
 * @property string $ip 
 * @property int $show 
 * @property \Carbon\Carbon $created_at 
 */
class Company extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'company';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'int', 'show' => 'integer', 'created_at' => 'datetime'];
}
