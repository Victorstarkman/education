<?php
##################################################
# Description: This file contains the Insert dates class.
##################################################
# Authot: Romulo Henrique D. S.
# Version: 1.0
# Date: 2021/12/13
# GitHub: https://github.com/romulo126/
# LinkedIn: https://www.linkedin.com/in/romulo-henrique-364976133/
##################################################
# Copyright (c) 2020 Romulo Henrique D. S.
##################################################

namespace sql\genericsqlformat\Insert;

use sql\genericsqlformat\Conect\Conect;

class Insert extends Conect
{
    private $from;
    private $query;
    private $columns;
    private $columnsValues = '';
    private $values;
    private $conect;
    private $debug;

    public function __construct($debug = false)
    {
        parent::__construct();
        $this->debug = $debug;
        $this->conect = $this->getPdo($debug);
    }

    public function setFrom(string $from)
    {
        $this->from = $from;
    }

    public function setDates(array $values)
    {
        $this->columns = implode(' , ', array_map(function ($value, $key) {
            if (is_numeric($key)) {
                if ($this->debug) {
                    echo  $key . ' is numeric';
                }
                die;
            }
            $this->values[':' . $key . 'GenericSQLFormatInsert'] = $value;
            if ($this->columnsValues != '')
                $this->columnsValues .= ' , ';
            $this->columnsValues .= ':' . $key . 'GenericSQLFormatInsert';
            return $key;
        }, array_values($values), array_keys($values)));
    }

    public function getQuery()
    {
        return "INSERT INTO {$this->from} ({$this->columns}) VALUES ({$this->columnsValues})";
    }

    private function bindParamInsert()
    {
        array_map(function ($value, $key) {
            $this->query->bindParam($key, $value);
        }, array_values($this->values), array_keys($this->values));
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
        $this->bindParamInsert();
        if ($this->query->execute()) {
            return true;
        } else {
            if ($this->debug) {
                var_dump($this->query->errorInfo());
            }
        }
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
