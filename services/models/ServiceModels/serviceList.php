<?php
include_once '../models/response.php';
include_once 'serviceModal.php';

class ServicesList
{
    public $services;

    // Db connection
    public function __construct()
    {
        $this->services = new Service();
    }
}
