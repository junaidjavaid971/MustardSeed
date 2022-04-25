<?php
include_once '../models/response.php';
include_once 'membershipModel.php';

class MembershipsList
{
    public $memberships;

    // Db connection
    public function __construct()
    {
        $this->memberships = new Memberships();
    }
}
