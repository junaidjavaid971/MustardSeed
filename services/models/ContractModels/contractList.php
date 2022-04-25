<?php
include_once '../models/response.php';
include_once 'contracts.php';

class ContractsList
{
    public $contracts;

    // Db connection
    public function __construct()
    {
        $this->contracts = new Contracts();
    }
}
