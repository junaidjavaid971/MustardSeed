<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/sessions.php';
include_once '../services/UserManagementService/membership.php';
include_once '../services/UserManagementService/services.php';
include_once '../services/UserManagementService/profile.php';
include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';

$database = new Database();
$db = $database->getConnection();

$profile = new Profile($db);
$session = new Session();
$membership = new Membership($db);

$data = $_POST['Data'];

if ($data == null) {
    $tempRes = json_decode(file_get_contents('php://input'), true);
    $data = $tempRes['Data'];
}
$lvl = $data['lvl'];

if (strcmp($lvl, "1") == 0) {
    
    $email = $session->getEmail();

    if (empty($email)) {
        $email = $data['email'];
    }

    echo json_encode($membership->getCurrentMembershipPlan($email));
} else if (strcmp($lvl, "2") == 0) {
    if (strcmp($accountLevel, "5")) {
        $session->updateAccountLevel("6");
    }
    $membership->currentPlan = $data['plan'];
    $price = $data['amount'];
    $membership->expiresOn = $data['expiresOn'];
    $membership->purchasedOn = $data['purchasedOn'];

    $membership->userEmail = $session->getEmail();

    $membership->userEmail = $session->getEmail();

    if (empty($membership->userEmail)) {
        $membership->userEmail = $data['email'];
    }

    $response = $membership->storePaymentDetailsInDB($price, "");
    if (strcmp($response->code, "00") == 0) {
        $amount =  $membership->getWalletAmount();
        $amount = (float)$amount - (float)$price;
        $membership->updateWallet($amount);

        $accountLevel = $session->getAccountLevel();
        if ($accountLevel == 5) {
            $session->updateAccountLevel(6);
        }
    }
    echo json_encode($response);
} else if (strcmp($lvl, "3") == 0) {
    $membership->currentPlan = $data['plan'];
    $price = $data['amount'];
    $membership->expiresOn = $data['expiresOn'];
    $membership->purchasedOn = $data['purchasedOn'];
    $chargeId = $data['chargeId'];
    $membership->userEmail = $session->getEmail();

    if (empty($membership->userEmail)) {
        $membership->userEmail = $data['email'];
    }

    $accountLevel = $session->getAccountLevel();
    if (strcmp($accountLevel, "5")) {
        $session->updateAccountLevel("6");
    }

    $response = $membership->storePaymentDetailsInDB($price, $chargeId);
    echo json_encode($response);
}
