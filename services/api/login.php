
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../services/UserManagementService/login.php';
include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';


$database = new Database();

$db = $database->getConnection();

$item = new Login($db);

$data = $_POST['Data'];

if ($data == null) {
    $tempRes = json_decode(file_get_contents('php://input'), true);
    $data = $tempRes['Data'];
}

$response = new Response();

$lvl = $data['lvl'];
$item->email = $data['email'];
$item->password = $data['password'];

if (strcmp($lvl, "1") == 0) {
    $response = $item->login();
} else if (strcmp($lvl, "2") == 0) {
    $response = $item->closeAccount();
}
if (strcmp($response->code, ResponseCodes::SUCCESS) == 0) {
    echo json_encode($response);
} else {
    echo json_encode($response);
}
