create table `pages` (
    `id` bigint unsigned not null auto_increment primary key,
    `page_total` int not null,
    `current_page` int not null,
    `total_file_downloaded` int not null,
    `total_file` int not null,
    `end` tinyint(1) not null default '0',
    `created_at` timestamp null,
    `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';