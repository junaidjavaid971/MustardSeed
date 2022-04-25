<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/sessions.php';
include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';

$session = new Session();

$data = $_POST['Data'];

if ($data == null) {
    $tempRes = json_decode(file_get_contents('php://input'), true);
    $data = $tempRes['Data'];
}

$lvl = $data['lvl'];
$email = $data['email'];
$act = $data['act'];

if (strcmp($act, "1") == 0) {
    $session->saveSession($email, $lvl);
    echo "session saved";
} else if (strcmp($act, "2") == 0) {
    $session->destorySession();
    echo "Logged out successfully!";
} else if (strcmp($act, "3") == 0) {
    echo $session->getAccountLevel() . "-" . $session->getEmail();
}
