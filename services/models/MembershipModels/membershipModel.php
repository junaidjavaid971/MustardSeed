<?php
include_once 'response.php';

class Memberships
{
    public $membership;

    // Db connection
    public function __construct()
    {
        $this->membership = new MembershipModel();
    }
}

class MembershipModel
{

    // Columns
    public $id;
    public $userEmail;
    public $plan;
    public $purchasedOn;
    public $expiresOn;

    // Db connection
    public function __construct()
    {
    }
}
