<?php

namespace Repository\User;

use sql\genericsqlformat\Select\Select;

class User {
    
        private $sqlSelect;
    
        public function __construct() {
            $this->sqlSelect = new Select();
        }
    
        public function getUser(): array {
            $this->sqlSelect->setFrom('user_login');
            $this->sqlSelect->setColumns(['user', 'password']);
            $this->sqlSelect->setWhere(['active' => true, 'deleted_at' => null], ['active' => '=', 'deleted_at' => 'is'], ['&&']);
            $this->sqlSelect->setLimit(1);
            $return = $this->sqlSelect->run();
            
            return $return[0] ?? [];
        }
}