<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property int $user_id 用户ID
 * @property int $station_id 岗位ID
 * @property int $type 评论类型 1-黑评 2-好评
 * @property int $company_id 公司ID
 * @property string $content 帖子内容
 * @property string $ip 评论IP地址
 * @property int $show 是否展示 0-否 1-是
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Post extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'post';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'int', 'user_id' => 'integer', 'station_id' => 'integer', 'type' => 'integer', 'company_id' => 'integer', 'show' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }
}
