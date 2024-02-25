<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property int $user_id 
 * @property int $compant_id 
 * @property int $post_id 
 * @property int $parent_id 
 * @property string $comment 
 * @property string $ip 
 * @property int $show 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Comment extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'comment';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'int', 'user_id' => 'integer', 'compant_id' => 'integer', 'post_id' => 'integer', 'parent_id' => 'integer', 'show' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
