<?php
include_once 'response.php';

class Contracts
{
    public $contract;

    // Db connection
    public function __construct()
    {
        $this->contract = new ContractModel();
    }
}

class ContractModel
{

    // Columns
    public $id;
    public $contractTitle;
    public $contractDetails;
    public $postedBy;
    public $userEmail;
    public $contractDate;
    public $city;
    public $applicationsSubmitted;
    public $isDocumentAttached;
    public $attachmentExtension;

    // Db connection
    public function __construct()
    {
    }
}
