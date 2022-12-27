<?php

namespace Repository\User;

use Repository\RepositoryBase;

class User extends RepositoryBase {
    
        public function getUser(): array {
            $this->setFrom('user_login');
            $this->select();
            $this->setColumns(['user', 'password']);
            $this->setLimit(1);
            return $this->getSelect()[0] ?? [];
        }
}