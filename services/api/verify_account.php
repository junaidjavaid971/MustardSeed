<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../services/UserManagementService/login.php';
include_once '../services/UserManagementService/user.php';
include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';

$database = new Database();
$db = $database->getConnection();

$item = new User($db);

if (isset($_GET['user_email'])) {
    $item->email = $_GET['user_email'];
    $item->verified = 1;
    if ($item->activateAccount()) {
        header("Location: ../../account-verification-2.html");
    }
}
