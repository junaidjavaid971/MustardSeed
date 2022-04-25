<?php
    include_once '../ResponseModels/response.php';
    include_once 'supervisionReport.php';

    class SupervisionList{
        public $supervisionReports;

        // Db connection
        public function __construct(){
            $this->supervisionReports = new SupervisionReport();
        }
    }
