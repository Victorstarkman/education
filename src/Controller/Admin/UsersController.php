<?php
namespace App\Controller;

use App\Controller\AppController;


class UsersController extends AppController{
    
	public function initialize(): void
    {
        parent:: initialize();
        $this->viewBuilder()->setLayout('admin');
    }
    public function index()
    {
        $users = $this->paginate($this->Users);
        $this->set(compact('users'));
    }
   

}