<?php
##################################################
# Description: This file contains the Conect class.
##################################################
# Authot: Romulo Henrique D. S.
# Version: 1.0
# Date: 2021/12/11
# GitHub: https://github.com/romulo126/
# LinkedIn: https://www.linkedin.com/in/romulo-henrique-364976133/
##################################################
# Copyright (c) 2020 Romulo Henrique D. S.
##################################################

namespace sql\genericsqlformat\Conect;

use \PDO;

use sql\genericsqlformat\Config\Config;

class Conect extends Config
{
    private $pdo;
    private $error = false;

    public function __construct()
    {
        try{
            parent::__construct();
            $this->pdo = new PDO(
            $this->getDsn(),
            $this->getUser(),
            $this->getPassword()
            );
        }catch(\PDOException $e){
            
            $this->error = $e->getMessage();
            echo $this->error.' error';die;
        }
    }

    public function getPdo($debug = false)
    {
        if ($debug) {
            if($this->error){
                return $this->error;
            }
        }

        if($this->error){
            return false;
        }

        return $this->pdo;
    }

}