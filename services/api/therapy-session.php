<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/sessions.php';

include_once '../services/TherapySessionService.php';

include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';

include_once '../models/TherapySessionModels/therapySessionList.php';
include_once '../models/TherapySessionModels/therapySessionModel.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$database = new Database();
$db = $database->getConnection();

$therapySession = new TherapySessionService($db);
$session = new Session();

$data = $_POST['Data'];

if ($data == null) {
    $tempRes = json_decode(file_get_contents('php://input'), true);
    $data = $tempRes['Data'];
}

$lvl = $data['lvl'];

if (strcmp($lvl, "1") == 0) {
    //CreateT Therapy Session By Therapist/Manager/LA

    $email = $session->getEmail();

    $therapySessionModel = new TherapySessionModel();
    $therapySessionModel->sessionType = $data['sessionType'];
    $therapySessionModel->sessionDuration = $data['sessionDuration'];
    $therapySessionModel->sessionBreaks = $data['sessionBreak'];
    $therapySessionModel->sessionPrice = $data['sessionPrice'];
    $therapySessionModel->mondayTimings = $data['mondayTimings'];
    $therapySessionModel->tuesdayTimings = $data['tuesdayTimings'];
    $therapySessionModel->wednesdayTimings = $data['wednesdayTimings'];
    $therapySessionModel->thursdayTimings = $data['thursdayTimings'];
    $therapySessionModel->fridayTimings = $data['fridayTimings'];
    $therapySessionModel->saturdayTimings = $data['saturdayTimings'];
    $therapySessionModel->sundayTimings = $data['sundayTimings'];
    $therapySessionModel->futureBookings = $data['futureBookings'];
    $therapySessionModel->bookingMode = $data['bookingMode'];
    $therapySessionModel->isAttandeesAllowed = $data['attendeesAllowed'];
    $therapySessionModel->isDiffPayeeAllowed = $data['diffPayeeAllowed'];
    $therapySessionModel->isSeriesOfSessions = $data['isSeriesOfSessions'];
    $therapySessionModel->sessionSeries = $data['sessionSeries'];
    $therapySessionModel->sessionVenues = $data['sessionVenues'];
    $therapySessionModel->isRecordingAllowed = $data['isRecordingAllowed'];
    $therapySessionModel->offeredBy = $data['offeredBy'];

    echo json_encode($therapySession->storeTherapySessionInDatabase($therapySessionModel));
} else if (strcmp($lvl, "2") == 0) {

    // Get all therapy sessions for a single user

    $session = new Session();
    $email = $data['email'];

    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "Therapy Sessions for " . $email;
    $response->data = $therapySession->getTherapySessionsForSingleUser($email);
    echo json_encode($response);
} else if (strcmp($lvl, "3") == 0) {
    // Get all therapy sessions
    
    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "Therapy Sessions";
    $response->data = $therapySession->getTherapySessions();
    echo json_encode($response);
} else if (strcmp($lvl, "4") == 0) {
    // Get single therapy session

    $therapySessionID = $data['sessionID'];

    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "Therapy Session";
    $response->data = $therapySession->getSpecificTherapySessionByID($therapySessionID);
    echo json_encode($response);
} else if (strcmp($lvl, "5") == 0) {
    // Delete therapy session
    $therapySessionID = $data['sessionID'];
    $response = $therapySession->deleteSession($therapySessionID);
    echo json_encode($response);
} else if (strcmp($lvl, "6") == 0) {
    // Book Therapy Session
    $therapySessionService = TherapySessionService::withStripeToken('sk_test_51DIxM0IIBrLGUPv5nCv5PUA5p8Ahs3MY5ZzGcSyolhlHFo5I71fUITJvl40kZNkpCFQKMCE6zZ7reyZrLvHFNeHa009tzRII22', $db);

    $name = $data['bookieName'];
    $email = $data['email'];
    $price  = $data['price'];
    $sessionID  = $data['sessionID'];
    $stripeToken  = $data['token'];

    $customer = $therapySessionService->addCustomer($name, $email);
    $paymentResponse = $therapySessionService->chargeAmount($stripeToken, $price);

    if (
        $paymentResponse['amount_refunded'] == 0 && empty($paymentResponse['failure_code']) &&
        $paymentResponse['paid'] == 1 && $paymentResponse['captured'] == 1
    ) {
        $charge = $paymentResponse['id'];
        $amountPaid = $paymentResponse['amount'];
        $balanceTransaction = $paymentResponse['balance_transaction'];
        $paidCurrency = $paymentResponse['currency'];
        $paymentStatus = $paymentResponse['status'];

        if ($paymentStatus == 'succeeded') {
            $paymentObject = array($charge, $amountPaid, $customer->id, $email);
            $paymentID = $therapySessionService->storePaymentDetailsInDB($paymentObject);

            $response = $therapySessionService->bookTherapySession($paymentID, $sessionID, $email);
            echo json_encode($response);
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Payment Failed.";
            echo json_encode($response);
        }
    } else {
        $response = new Response();
        $response->code = ResponseCodes::FAILURE;
        $response->desc = "Payment Failed!";
        echo json_encode($response);
    }
} else if (strcmp($lvl, "7") == 0) {

    // Get Booked sessions for a single user

    $session = new Session();
    $email = $data['email'];

    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "Therapy Sessions for " . $email;
    $response->data = $therapySession->getBookedTherapySessionsForSingleUser($email);
    echo json_encode($response);
}
