Requisitos:
    Docker

Env:
 ```env
    - GENERICSQLFORMAT_HOST=mysql_docker_bot
    - GENERICSQLFORMAT_USER=bot_mysql
    - GENERICSQLFORMAT_PASSWORD=123456
    - GENERICSQLFORMAT_DATABASENAME=bot
    - GENERICSQLFORMAT_PORT=3306
    - GENERICSQLFORMAT_DRIVE=mysql
    - APP_VERSION=1.4.4
    - SLEEP=true
    - PATHFBOOT=/var/www/filesBot/

docker exec -it php_7_4_abc_gov_ag bash

cd /var/www/bot && composer install
