<?php
##################################################
# Description: This file contains the Where class.
##################################################
# Authot: Romulo Henrique D. S.
# Version: 1.0
# Date: 2021/12/11
# GitHub: https://github.com/romulo126/
# LinkedIn: https://www.linkedin.com/in/romulo-henrique-364976133/
##################################################
# Copyright (c) 2020 Romulo Henrique D. S.
##################################################

namespace sql\genericsqlformat\Where;

use sql\genericsqlformat\Conect\Conect;

class Where extends Conect
{
    private $where = false;
    private $arrayValues = [];
    private $query;

    public function __construct()
    {
    }

    public function setWhere(array $wherevalues, array $whereoperators, array $wherelogics = [])
    {
        $wherekeysvalues = array_keys($wherevalues);
        $wherekeysoperators = array_keys($whereoperators);

        $this->where = implode(' ', array_map(function ($value, $key, $operator, $operatorkey, $logics) {
            if (is_numeric($operatorkey)) {
                if (!empty($value)) {
                    $this->arrayValues[":" . $key . 'GenericSQLFormatWhere'] = $value;
                    $keyoperation = ":" . $key . 'GenericSQLFormatWhere';
                    return ' ' . $key . ' ' . $operator . ' ' . $keyoperation . ' ' . $logics;
                }
                return ' ' . $key . ' ' . $operator . ' null' . ' ' . $logics;
            } else {
                if (!empty($value)) {
                    $this->arrayValues[':' . $operatorkey . 'GenericSQLFormatWhere'] = $value;
                    $keyoperation = ":" . $operatorkey . 'GenericSQLFormatWhere';
                    return ' ' . $operatorkey . ' ' . $operator . ' ' . $keyoperation . ' ' . $logics;
                }
                return ' ' . $operatorkey . ' ' . $operator . ' null'  . ' ' . $logics;
            }
        }, $wherevalues, $wherekeysvalues, $whereoperators, $wherekeysoperators, $wherelogics));
    }

    public function getWhere()
    {
        return $this->where;
    }

    public function getArrayValues()
    {

        return $this->arrayValues;
    }

    public function bindParamWhere($query)
    {
        $this->query = $query;

        array_map(function ($value, $key) {
            $this->query->bindParam($key, $value);
        }, $this->arrayValues, array_keys($this->arrayValues));

        return $this->query;
    }
}
