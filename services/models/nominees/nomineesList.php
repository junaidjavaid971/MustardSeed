<?php
    include_once '../ResponseModels/response.php';
    include_once 'nominees.php';

    class NomineesList{
        public $nominees;

        // Db connection
        public function __construct(){
            $this->nominees = new Nominees();
        }
    }
