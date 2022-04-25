<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../services/UserManagementService/forgotpassword.php';
include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';

$database = new Database();
$db = $database->getConnection();
$resetPassword = new ResetPassword($db);

$data = $_POST['Data'];

if ($data == null) {
    $tempRes = json_decode(file_get_contents('php://input'), true);
    $data = $tempRes['Data'];
}

$lvl = $data['lvl'];
$resetPassword->email = $data['email'];

if (strcmp($lvl, "1") == 0) {
    $response = $resetPassword->sendPasswordResetEmail();
} else if (strcmp($lvl, "2") == 0) {
    $verCode = $data['verCode'];
    $response = $resetPassword->verifyResetPasswordCode($verCode);
} else if (strcmp($lvl, "3") == 0) {
    $newPassword = $data['newPassword'];
    $response = $resetPassword->changePassword($newPassword);
} else if (strcmp($lvl, "4") == 0) {
    $email = $data['email'];
    $response = $resetPassword->sendSignupEmail($email, "");
    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "OTP Email sent to your email address!";
}
if (strcmp($response->code, ResponseCodes::SUCCESS) == 0) {
    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    if (strcmp($lvl, "1") == 0) {
        $response->desc = "Password Reset Email Send";
    } else if (strcmp($lvl, "2") == 0) {
        $response->desc = "Verification code verified";
    } else if (strcmp($lvl, "3") == 0) {
        $response->desc = "Your password has been changed.";
    }
    echo json_encode($response);
} else {
    echo json_encode($response);
}
