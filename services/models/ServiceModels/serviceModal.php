<?php
include_once 'response.php';

class Service
{
    public $service;

    // Db connection
    public function __construct()
    {
        $this->service = new ServicesModal();
    }
}

class ServicesModal
{

    // Columns
    public $id;
    public $serviceTitle;
    public $serviceDesc;
    public $serviceDuration;
    public $serviceCost;
    public $serviceType;
    public $email;

    // Db connection
    public function __construct()
    {
    }
}
