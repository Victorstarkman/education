# PHP Generic Query SQL

## Composer Install
    composer require sql/genericsqlformat

## Description
    simple query generator for PHP

## OBS:
- All classes have manualQueryBuilder() method where you can pass a string with the query
- The database connection settings are in the Config folder in the Config.php file
- If you don't want to use environment edit the config file

## Required
    - PHP 7.0+
    - Composer
    - ENVIRONMENT VARIABLES

## ENVIRONMENT VARIABLES
    - GENERICSQLFORMAT_HOST
    - GENERICSQLFORMAT_USER
    - GENERICSQLFORMAT_PASSWORD
    - GENERICSQLFORMAT_DATABASENAME
    - GENERICSQLFORMAT_PORT // 5432 (PostgreSQL), 3306 (MySQL), 1521 (Oracle)
    - GENERICSQLFORMAT_DRIVE //mysql, pgsql, sqlite, sqlsrv, oci, mssql, dblib, oracle,

## Usage


- Select:
    ```php
    <?php
        require_once __DIR__.'vendor/autoload.php';

        //library
        use sql\genericsqlformat\Select\Select;

        //query
        //enable debug mode
            $select = new Select(true);
        //no debug mode
            $select = new Select();
        $select->setFrom('users');
        $select->setColumns(['id', 'nameuser']);
        $select->setWhere(['id' => 1,'nameuser'=>'teste'],['nameuser'=>'=','id'=>'like'], ['OR']);
        $select->setGroup(['id']);
        $select->setOrder(['id']);
        $select->setLimit(1);
        $select->run();
    ?>
    ```
- Select Join
    ```php
    <?php
        require_once __DIR__.'vendor/autoload.php';

        //library
        use sql\genericsqlformat\Select\Select;

        //query
        //enable debug mode
            $select = new Select(true);
        //no debug mode
            $select = new Select();
        $select->setFrom('users');
        $select->setColumns(['id', 'nameuser']);
        $select->setJoin('LEFT','users_groups', 'users.id = users_groups.id_user');
        $select->setJoin('FULL','users_groups', 'users.id = users_groups.id_user');
        $select->setJoin('RIGHT','users_groups', 'users.id = users_groups.id_user');
        $select->setJoin('INNER','users_groups', 'users.id = users_groups.id_user');
        $select->setWhere(['id' => 1,'nameuser'=>'teste'],['nameuser'=>'=','id'=>'like'], ['OR']);
        $select->setGroup(['id']);
        $select->setOrder(['id']);
        $select->setLimit(1);
        $select->run();
    ?>
    ```
- Update:
    ```php
    <?php
        require_once __DIR__.'vendor/autoload.php';

        //library
        use sql\genericsqlformat\Update\Update;

        //query
        //enable debug mode
            $update = new Update(true);
        //no debug mode
            $update = new Update();
        $update->setFrom('users');
        $update->setColumnsSet(['id' => 1,'nameuser'=>'teste']);
        $update->setWhere(['id' => 1,'nameuser'=>'teste'],['nameuser'=>'=','id'=>'like'], ['OR']);
        $update->run();
    ?>
    ```
- Insert:
    - OBS:
        - The setDates method expects an array where the key is the table column and the value and value to be inserted into that column
    ```php
    <?php
        require_once __DIR__.'vendor/autoload.php';

        //library
        use sql\genericsqlformat\Insert\Insert;

        //query
        //enable debug mode
            $insert = new Insert(true);
        //no debug mode
            $insert = new Insert();
        $insert->setFrom('users');
        $insert->setDates(['nameuser' => 'teste4', 'birthday' => '2021-12-13','email'=>'tetse1','passworduser'=>'teste2','createdat'=>'2020-12-13']);
        $insert->run();
    ?>
    ```
- Delete:
    ```php
    <?php
        require_once __DIR__.'vendor/autoload.php';

        //library
        use sql\genericsqlformat\Delete\Delete;

        //query
        //enable debug mode
            $delete = new Delete(true);
        //no debug mode
            $delete = new Delete();
        
        $delete->setFrom("users");
        $delete->setWhere(["id"=>1], ["="]);
        $delete->run();
    ?>
    ``` 