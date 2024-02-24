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
}
