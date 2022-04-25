<?php

class Supervision
{
    public $supervisionReport;

    // Db connection
    public function __construct()
    {
        $this->supervisionReport = new SupervisionReport();
    }
}

class SupervisionReport
{

    // Columns
    public $id;
    public $userEmail;
    public $supervisorEmail;
    public $contactName;
    public $contactDetails;
    public $contactDate;
    public $contactTime;
    public $isDocumentAttached;
    public $attachmentExtension;

    // Db connection
    public function __construct()
    {
    }
}
