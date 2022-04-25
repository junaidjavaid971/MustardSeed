<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/sessions.php';
include_once '../services/UserManagementService/supervisor.php';
include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';

$database = new Database();
$db = $database->getConnection();

$supervisor = new Supervisor($db);

$data = $_POST['Data'];

if ($data == null) {
    $tempRes = json_decode(file_get_contents('php://input'), true);
    $data = $tempRes['Data'];
}

$lvl = $data['lvl'];

if (strcmp($lvl, "1") == 0) {
    $email = $data['email'];

    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "Nominations";
    $response->data = $supervisor->getNomination($email);
    echo json_encode($response);
} else if (strcmp($lvl, "2") == 0) {
    $session = new Session();
    $supervisor->userEmail = $session->getEmail();
    $supervisor->supervisorEmail = $data['supervisorEmail'];
    $supervisor->contactName = $data['contactName'];
    $supervisor->contactDetails = $data['contactDetails'];
    $supervisor->contactDate = $data['contactDate'];
    $supervisor->contactTime = $data['contactTime'];
    $supervisor->attachmentUrl = $data['attachmentUrl'];
    $supervisor->attachmentExt = $data['attachmentExt'];

    if (empty($supervisor->userEmail)) {
        $supervisor->userEmail = $data['email'];
    }

    echo json_encode($supervisor->sendReportForSupervision());
} else if (strcmp($lvl, "3") == 0) {
    $session = new Session();
    $supervisor->userEmail = $session->getEmail();
    
    if (empty($supervisor->userEmail)) {
        $supervisor->userEmail = $data['email'];
    }
    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "Supervision Reports";
    $response->data = $supervisor->getSupervisionReports();
    echo json_encode($response);
}
