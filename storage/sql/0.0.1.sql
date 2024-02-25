-- 安装一些依赖包
-- composer require phpmailer/phpmailer
-- composer require limingxinleo/hyperf-utils
-- composer require hyperf/command
-- composer require symfony/var-dumper --dev
-- composer require hyperf/di
-- composer require hyperf/redis
-- composer require hyperf/paginator



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

CREATE TABLE `company`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `name`       varchar(255) NOT NULL,
    `station`    varchar(255) NOT NULL,
    `city`       varchar(50) DEFAULT NULL,
    `ip`         varchar(50)  NOT NULL,
    `show`       tinyint      NOT NULL,
    `created_at` datetime     NOT NULL,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1854
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `comment`
(
    `id`         int                                                   NOT NULL AUTO_INCREMENT,
    `user_id`    int      DEFAULT NULL,
    `company_id` int      DEFAULT NULL,
    `post_id`    int      DEFAULT NULL,
    `parent_id`  int      DEFAULT '0',
    `comment`    text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `ip`         varchar(50)                                           NOT NULL,
    `show`       tinyint                                               NOT NULL,
    `created_at` datetime                                              NOT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 300
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `post`
(
    `id`         int                                                          NOT NULL AUTO_INCREMENT,
    `user_id`    int      DEFAULT NULL COMMENT '用户ID',
    `station_id` tinyint  DEFAULT '0' COMMENT '岗位ID',
    `type`       tinyint  DEFAULT '1' COMMENT '评论类型 1-黑评 2-好评',
    `company_id` int      DEFAULT NULL COMMENT '公司ID',
    `content`    text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci        NOT NULL COMMENT '帖子内容',
    `ip`         varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '评论IP地址',
    `show`       tinyint                                                      NOT NULL COMMENT '是否展示 0-否 1-是',
    `created_at` datetime                                                     NOT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 825
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;