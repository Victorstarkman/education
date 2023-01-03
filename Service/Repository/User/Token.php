<?php

namespace Repository\User;

use Repository\RepositoryBase;

class Token extends RepositoryBase
{



    public function getToken(): array
    {

        $this->setFrom('token_user');
        $this->select();
        $this->setColumns(['id', 'token']);
        $this->setLimit(1);

        return $this->getSelect()[0] ?? [];
    }

    public function setToken(string $token): bool
    {
        $this->setFrom('token_user');
        $token = ['id' => 1, 'token' => $token, 'created_at' => date('Y-m-d H:i:s')];

        return $this->insert($token);;
    }

    public function deleteToken(int $id): void
    {
        $this->setFrom('token_user');
        $this->delete();
    }
}
