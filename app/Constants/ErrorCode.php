<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    public const SERVER_ERROR = 500;

    /**
     * @message("验证码已发送，请稍后再试!")
     */
    public const AUTH_CAPTCHA_SEND = 10001;

    /**
     * @message("邮件发送失败，请稍后再试!")
     */
    public const AUTH_EMAIL_SEND_ERROR = 10002;

    /**
     * @message("验证码错误，请重新输入!")
     */
    public const AUTH_CAPTCHA_ERROR = 10003;
    /**
     * @message("该邮箱已被注册")
     */
    public const AUTH_EMAIL_REGISTERED = 10004;
    /**
     * @message("该邮箱未注册")
     */
    public const AUTH_EMAIL_NOT_FOUND = 10005;
    /**
     * @message("密码错误")
     */
    public const AUTH_PASSWORD_ERROR = 10006;
    /**
     * @message("token 非法")
     */
    public const AUTH_FAIL = 10007;
}
