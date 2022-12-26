<?php
##################################################
# Description: This file contains the Select class.
##################################################
# Authot: Romulo Henrique D. S.
# Version: 2.0
# Date: 2021/12/12
# GitHub: https://github.com/romulo126/
# LinkedIn: https://www.linkedin.com/in/romulo-henrique-364976133/
##################################################
# Copyright (c) 2020 Romulo Henrique D. S.
##################################################

namespace sql\genericsqlformat\Select;

use sql\genericsqlformat\Conect\Conect;
use sql\genericsqlformat\Where\Where;



class Select extends Conect
{
    private $from = false;
    private $columns = false;
    private $where = false;
    private $order = false;
    private $limit = false;
    private $group = false;
    private $query = false;
    private $conect = false;
    private $debug = false;
    private $joinfrom = false;

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

    public function setColumns(array $columns)
    {
        $this->columns = implode(', ', $columns);
    }

    public function setWhere(array $wherevalues, array $whereoperators, array $wherelogics = [])
    {
        $this->where->setWhere($wherevalues, $whereoperators, $wherelogics);
    }


    public function setOrder(array $order)
    {
        $this->order = implode(', ', $order);
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }

    public function setGroup(array $group)
    {
        $this->group = implode(', ', $group);
    }

    public function setJoin(string $typejoin, string $fromjoin, string $onjoin = null)
    {
        if (!is_null($onjoin)) {
            if (strtoupper($typejoin) == strtoupper('JOIN')) {
                $this->joinfrom .= ' ' . strtoupper($typejoin) . ' ' . $fromjoin . ' ON ' . $onjoin;
            } else {
                $this->joinfrom .= ' ' . strtoupper($typejoin) . ' JOIN ' . $fromjoin . ' ON ' . $onjoin;
            }
        } else {
            if (strtoupper($typejoin) == strtoupper('JOIN')) {
                $this->joinfrom .= ' ' . strtoupper($typejoin) . ' ' . $fromjoin;
            } else {
                $this->joinfrom .= ' ' . strtoupper($typejoin) . ' JOIN ' . $fromjoin;
            }
        }
    }

    public function run()
    {
        if ($this->debug) {
            echo "\n";
            echo $this->getQuery();
            echo "\n";
        }
        
        $this->query = $this->conect->prepare($this->getQuery());
        if ($this->where->getWhere()) {
            $this->query = $this->where->bindParamWhere($this->query);
        }
        if ($this->query->execute()) {
            return $this->query->fetchAll(\PDO::FETCH_ASSOC);
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
            return $this->query->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            if ($this->debug) {
                var_dump($this->query->errorInfo());
            }
        }
        return false;
    }

    public function getQuery()
    {
        $query = "SELECT $this->columns FROM $this->from";
        if ($this->joinfrom) {
            $query .= $this->joinfrom;
        }
        if ($this->where->getWhere()) {
            $query .= " WHERE" . $this->where->getWhere();
        }
        if ($this->group) {
            $query .= " GROUP BY $this->group";
        }
        if ($this->order) {
            $query .= " ORDER BY $this->order";
        }
        if ($this->limit) {
            $query .= " LIMIT $this->limit";
        }
        return $query . ' ;';
    }
}
