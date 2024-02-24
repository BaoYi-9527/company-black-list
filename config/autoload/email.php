<?php

declare(strict_types=1);

use function Hyperf\Support\env;

return [
    'username' => env('SMTP_EMAIL_USERNAME', ''),
    'password' => env('SMTP_EMAIL_PASSWORD', ''),
    'from'     => env('SMTP_EMAIL_FROM', ''),
    'name'     => env('SMTP_EMAIL_NAME', ''),
];