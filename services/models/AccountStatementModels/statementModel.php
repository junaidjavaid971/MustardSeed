<?php
include_once 'response.php';

class Statements
{
    public $statement;

    // Db connection
    public function __construct()
    {
        $this->statement = new StatementModel();
    }
}

class StatementModel
{

    // Columns
    public $userName;
    public $therapistName;
    public $therapyDate;
    public $bookingStatus;
    public $cost;

    // Db connection
    public function __construct()
    {
    }
}
