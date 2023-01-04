<?php
##################################################
# Description: This file contains the Delete dates class.
##################################################
# Authot: Romulo Henrique D. S.
# Version: 1.0
# Date: 2021/12/13
# GitHub: https://github.com/romulo126/
# LinkedIn: https://www.linkedin.com/in/romulo-henrique-364976133/
##################################################
# Copyright (c) 2020 Romulo Henrique D. S.
##################################################

namespace sql\genericsqlformat\Delete;

use sql\genericsqlformat\Conect\Conect;
use sql\genericsqlformat\Where\Where;

class Delete extends Conect
{
    private $from;
    private $where;
    private $query;
    private $conect;
    private $debug;

    public function __construct($debug = false)
    {
        parent::__construct();
        $this->where = new Where();
        $this->debug = $debug;
        $this->conect = $this->getPdo($debug);
    }

    public function setFrom(string $from)
    {
        $this->from = $from;
    }

    public function setWhere(array $wherevalues, array $whereoperators, array $wherelogics = [])
    {
        $this->where->setWhere($wherevalues, $whereoperators, $wherelogics);
    }

    public function getQuery()
    {
        if ($this->where->getWhere()) {
            return "DELETE FROM " . $this->from . " WHERE " . $this->where->getWhere() . " ;";
        }
        return "DELETE FROM " . $this->from." ;";
    }

    public function run()
    {
        if($this->debug)
        {
            echo "\n";
            echo $this->getQuery();
            echo "\n";
        }
        
        $this->query = $this->conect->prepare($this->getQuery());
        if ($this->where->getWhere()) {
            $this->where->bindParamWhere($this->query);
        }
        if ($this->query->execute()) {
            return true;
        } else {
            if ($this->debug) {
                var_dump($this->query->errorInfo());
            }
        }
        return false;
    }

    public function manualQueryBuilder(string $query)
    {
        $this->query = $this->conect->prepare($query);
        if ($this->query->execute()) {
            return true;
        } else {
            if ($this->debug) {
                var_dump($this->query->errorInfo());
            }
        }
        return false;
    }
}