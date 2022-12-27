create table `user_login` (
    `id` bigint unsigned not null auto_increment primary key,
    `user` varchar(255) not null,
    `password` varchar(255) not null,
    `active` tinyint(1) not null default '1',
    `created_at` timestamp null,
    `updated_at` timestamp null,
    `deleted_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

alter table `user_login` add unique `user_login_user_unique`(`user`);