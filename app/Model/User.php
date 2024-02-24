<?php

declare(strict_types=1);

namespace App\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $email 
 * @property string $password 
 * @property int $status 0:禁用,1:正常
 * @property string $head_img 
 * @property string $desc 
 * @property string $ip 
 * @property string $token 
 * @property string $captcha 
 * @property string $last_login_time 
 * @property int $login_times 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class User extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'user';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'int', 'status' => 'integer', 'login_times' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];


}
