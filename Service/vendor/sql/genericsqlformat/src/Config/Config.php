<?php
##################################################
# Description: This file contains the Config class for the DataBase.
##################################################
# Authot: Romulo Henrique D. S.
# Version: 1.0
# Date: 2021/12/11
# GitHub: https://github.com/romulo126/
# LinkedIn: https://www.linkedin.com/in/romulo-henrique-364976133/
##################################################
# Copyright (c) 2020 Romulo Henrique D. S.
##################################################

namespace sql\genericsqlformat\Config;

class Config
{
    private $host;
    private $user ;
    private $password ;
    private $databasename ;
    private $port ;// 5432 (PostgreSQL), 3306 (MySQL), 1521 (Oracle)
    private $driver ; //mysql, pgsql, sqlite, sqlsrv, oci, mssql, dblib, oracle, 

    public function __construct()
    {
        $this->host = getenv('GENERICSQLFORMAT_HOST');
        $this->user = getenv('GENERICSQLFORMAT_USER');
        $this->password = getenv('GENERICSQLFORMAT_PASSWORD');
        $this->databasename = getenv('GENERICSQLFORMAT_DATABASENAME');
        $this->port = intval(getenv('GENERICSQLFORMAT_PORT'));// 5432 (PostgreSQL), 3306 (MySQL), 1521 (Oracle)
        $this->driver = getenv('GENERICSQLFORMAT_DRIVE'); //mysql, pgsql, sqlite, sqlsrv, oci, mssql, dblib, oracle,

    }
    public function getHost()
    {
        return $this->host;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDatabase()
    {
        return $this->databasename;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getDsn()
    {
        return "{$this->driver}:host={$this->host};port={$this->port};dbname={$this->databasename};";
    }
}
