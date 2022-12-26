<?php

namespace Repository\User;

use sql\genericsqlformat\Select\Select;
use sql\genericsqlformat\Insert\Insert;
use sql\genericsqlformat\Delete\Delete;

class Token
{

    private $sqlSelect;
    private $sqlInsert;
    private $sqlDelete;

    public function __construct()
    {
        $this->sqlSelect = new Select();
        $this->sqlInsert = new Insert();
        $this->sqlDelete = new Delete();
    }

    public function getToken(): array
    {
        $this->sqlSelect->setFrom('token_user');
        $this->sqlSelect->setColumns(['id', 'token']);
        $this->sqlSelect->setLimit(1);

        return $this->sqlSelect->run()[0] ?? [];
    }

    public function setToken(string $token): bool
    {
        $this->sqlInsert->setFrom('token_user');
        $this->sqlInsert->setDates(['token' => $token, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        return $this->sqlInsert->run();
    }

    public function deleteToken(int $id): void
    {
        $this->sqlDelete->setFrom('token_user');
        $this->sqlDelete->setWhere(['id' => $id], ['id' => '=']);
        $this->sqlDelete->run();
    }
}
