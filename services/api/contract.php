<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';
include_once '../services/ContractServices/contract.php';
include_once '../services/UserManagementService/profile.php';
include_once '../config/database.php';
include_once '../config/sessions.php';

$data = $_POST['Data'];

if ($data == null) {
    $tempRes = json_decode(file_get_contents('php://input'), true);
    $data = $tempRes['Data'];
}

$database = new Database();
$db = $database->getConnection();
$contractService = new ContractService($db);
$profileService = new Profile($db);
$session = new Session();

$lvl = $data['lvl'];

if (strcmp($lvl, "1") == 0) {
    $contractService->contractTitle  = $data['contractName'];
    $contractService->contractDetails  = $data['contractDetails'];
    $contractService->attachmentUrl =  $data['attachmentUrl'];
    $contractService->attachmentExt =  $data['attachmentExt'];
    $contractService->contractPostDate = date('Y-d-m');
    $contractService->postedBy  = $session->getEmail();
    $contractService->userEmail = $session->getEmail();

    if (empty($contractService->postedBy)) {
        $contractService->postedBy = $data['postedBy'];
    }
    if (empty($contractService->userEmail)) {
        $contractService->userEmail = $data['userEmail'];
    }

    echo json_encode($contractService->storeContractInDatabase());
} else if (strcmp($lvl, "2") == 0) {
    $email = $session->getEmail();

    if (empty($email)) {
        $email = $data['email'];
    }
    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "Contracts";
    $response->data = $contractService->getContracts($email);
    echo json_encode($response);
} else if (strcmp($lvl, "3") == 0) {
    $contractService->appliedBy  = $session->getEmail();
    $contractService->invoiceContact  = $data['invoiceContact'];
    $contractService->invoiceEmail  = $session->getEmail();
    $contractService->invoiceTelephone =  $data['invoiceTelephone'];
    $contractService->contactRef1 =  $data['invoiceContactRef1'];
    $contractService->contactRef2 =  $data['invoiceContactRef2'];
    $contractService->invoiceAddress =  $data['invoiceAddress'];
    $contractService->purchaseOrder =  $data['purchaseOrder'];
    $contractService->contactName =  $data['comContactName'];
    $contractService->contactEmail =  $data['comContactEmail'];
    $contractService->contactTelephone =  $data['comContactTelephone'];
    $contractService->attachmentUrl =  $data['attachmentUrl'];
    $contractService->attachmentExt =  $data['attachmentExt'];
    $contractService->id =  $data['contractId'];
    $contractService->appliedOn = date('Y-d-m');

    if (empty($contractService->invoiceEmail)) {
        $contractService->invoiceEmail = $data['invoiceEmail'];
    }

    echo json_encode($contractService->storeApplication());
} else if (strcmp($lvl, "4") == 0) {
    $id = $data['id'];

    echo json_encode($contractService->getContractApplications($id));
} else if (strcmp($lvl, "5") == 0) {
    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "Contracts";
    $response->data = $contractService->getAllContracts();
    echo json_encode($response);
}
