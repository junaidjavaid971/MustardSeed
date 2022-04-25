<?php
    include_once '../ResponseModels/response.php';

    class Users{
        public $user;

        // Db connection
        public function __construct(){
            $this->user = new UserModel();
        }
    }

    class UserModel{

        // Columns
        public $id;
        public $name;
        public $email;
        public $role;
        public $contactNumber;
        public $address;
        public $country;
        public $state;
        public $city;
        public $zipCode;
        public $accreditation;
        public $qualification;
        public $college;
        public $passingYear;
        public $supervisorContactNumber;
        public $supervisorAddress;
        public $supervisorCountry;
        public $supervisorState;
        public $supervisorCity;
        public $supervisorZipCode;
        public $isClientSupervisor;
        public $termsAccepted;

        // Db connection
        public function __construct(){
        }
    }
