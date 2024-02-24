-- 安装一些依赖包
-- composer require phpmailer/phpmailer
-- composer require limingxinleo/hyperf-utils
-- composer require hyperf/command
-- composer require symfony/var-dumper --dev
-- composer require hyperf/di
-- composer require hyperf/redis


CREATE TABLE `user`
(
    `id`              int(11)      NOT NULL AUTO_INCREMENT,
    `name`            varchar(255) NOT NULL,
    `email`           varchar(255) NOT NULL,
    `password`        varchar(255) NOT NULL,
    `status`          tinyint(4)   NOT NULL DEFAULT 1 COMMENT '0:禁用,1:正常',
    `head_img`        varchar(255) NOT NULL DEFAULT '',
    `desc`            varchar(255) NOT NULL DEFAULT '',
    `ip`              varchar(255) NOT NULL DEFAULT '',
    `token`           varchar(255) NOT NULL DEFAULT '',
    `captcha`         varchar(20)  NOT NULL DEFAULT '',
    `last_login_time` datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `login_times`     int(11)      NOT NULL DEFAULT 0,
    `created_at`      datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;