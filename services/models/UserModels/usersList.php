<?php
    include_once '../ResponseModels/response.php';
    include_once 'usermodel.php';

    class UsersList{
        public $users;

        // Db connection
        public function __construct(){
            $this->users = new UserModel();
        }
    }
?>