create table `token_user` (
    `id` bigint unsigned not null auto_increment primary key,
    `token` varchar(255) not null,
    `created_at` timestamp null,
    `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';