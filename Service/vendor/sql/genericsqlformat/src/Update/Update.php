<?php
##################################################
# Description: This file contains the Update dates class.
##################################################
# Authot: Romulo Henrique D. S.
# Version: 1.0
# Date: 2021/12/13
# GitHub: https://github.com/romulo126/
# LinkedIn: https://www.linkedin.com/in/romulo-henrique-364976133/
##################################################
# Copyright (c) 2020 Romulo Henrique D. S.
##################################################

namespace sql\genericsqlformat\Update;

use sql\genericsqlformat\Conect\Conect;
use sql\genericsqlformat\Where\Where;

class Update extends Conect
{
    private $from;
    private $columnsSetUpdate;
    private $columnsSetUpdateValues;
    private $where;
    private $query;
    private $conect;
    private $debug;

    public function __construct($debug = false)
    {
        parent::__construct();
        $this->where = new Where();
        $this->conect = $this->getPdo($debug);
        $this->debug = $debug;
    }

    public function setFrom(string $from)
    {
        $this->from = $from;
    }

    public function setColumnsSet(array $columnsSet)
    {
        $this->columnsSetUpdate = implode(' , ', array_map(function ($column, $value) {
            if (is_numeric($column)) {
                if ($this->debug) {
                    echo  $column . ' is numeric';
                }
                die;
            } else {
                $this->columnsSetUpdateValues[":" . $column . 'GenericSQLFormatUpdate'] = $value;
                return $column . ' = :' . $column . 'GenericSQLFormatUpdate';
            }
        }, array_keys($columnsSet), array_values($columnsSet)));
    }

    private function bindParamUpdate()
    {
        array_map(function ($value, $key) {
            $this->query->bindParam($key, $value);
        }, $this->columnsSetUpdateValues, array_keys($this->columnsSetUpdateValues));
    }

    public function setWhere(array $wherevalues, array $whereoperators, array $wherelogics = [])
    {
        $this->where->setWhere($wherevalues, $whereoperators, $wherelogics);
    }

    public function getQuery()
    {
        if ($this->where->getWhere()) {
            return "UPDATE {$this->from} SET {$this->columnsSetUpdate} WHERE " . $this->where->getWhere() . ";";
        }
        return "UPDATE {$this->from} SET {$this->columnsSetUpdate} ;";
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
        $this->bindParamUpdate();
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