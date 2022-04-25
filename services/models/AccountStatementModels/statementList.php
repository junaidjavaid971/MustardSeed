<?php
include_once '../models/response.php';
include_once 'statementModel.php';

class StatementList
{
    public $statements;

    // Db connection
    public function __construct()
    {
        $this->statements = new Statements();
    }
}
