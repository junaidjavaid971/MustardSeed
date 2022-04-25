<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'services/config/sessions.php';
include_once 'services/models/response.php';
include_once 'services/models/responsecodes.php';

$session = new Session();

if ($session->isLoggedIn()) {
    $email = $session->getEmail();
    $lvl = $session->getAccountLevel();
    switch ($lvl) {
        case 0:
            header("Location: dashboard-unverified-incomplete-profile.html?email= +" .  urlencode($email));
            break;
        case 1:
            header("Location: verified-dashboard.html?email= +" .  urlencode($email));
            break;
        case 2:
            header("Location: cv-resume-dashboard.html?email= +" .  urlencode($email));
            break;
        case 3:
            header("Location: nominate-supervisor.html?email= +" .  urlencode($email));
            break;
        case 4:
            header("Location: dbs-validation-request.html?email= +" .  urlencode($email));
            break;
        case 5:
            header("Location: 9-membership.html?email= +" .  urlencode($email));
            break;
        case 6:
            header("Location: 10-membership-requested.html?email= +" .  urlencode($email));
            break;
    }
} else {
    header("Location: login1.html");
}
