<?php
include_once '../models/response.php';
include_once 'therapySessionModel.php';

class TherapySessionsList
{
    public $therapySessions;

    // Db connection
    public function __construct()
    {
        $this->therapySessions = new TherapySessionModel();
    }
}
