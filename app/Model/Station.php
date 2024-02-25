<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Station extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'station';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];


    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
