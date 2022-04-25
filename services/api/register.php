<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../services/UserManagementService/user.php';
include_once '../services/UserManagementService/forgotpassword.php';
include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = $_POST['Data'];

if ($data == null) {
    $tempRes = json_decode(file_get_contents('php://input'), true);
    $data = $tempRes['Data'];
}

$user->name = $data['name'];
$user->email = $data['email'];
$user->password = $data['password'];
$user->created = date('Y-m-d H:i:s');

$response = $user->createUser();

$profile = new ResetPassword($db);
$profile->sendSignupEmail($user->email, $user->name);

echo json_encode($response);
