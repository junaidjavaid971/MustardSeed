<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';
include_once '../services/BookingService/booking.php';
include_once '../config/database.php';
include_once '../config/sessions.php';

$database = new Database();
$session = new Session();
$db = $database->getConnection();

$data = $_POST['Data'];

if ($data == null) {
    $tempRes = json_decode(file_get_contents('php://input'), true);
    $data = $tempRes['Data'];
}

$lvl = $data['lvl'];

if (strcmp($lvl, "1") == 0) {
    //check if stripe token exist to proceed with payment
    $bookingService = BookingService::withStripeToken('sk_test_51DIxM0IIBrLGUPv5nCv5PUA5p8Ahs3MY5ZzGcSyolhlHFo5I71fUITJvl40kZNkpCFQKMCE6zZ7reyZrLvHFNeHa009tzRII22', $db);

    // get token and user details
    $stripeToken  = $data['token'];
    $bookingService->description  = $data['desc'];
    $bookingService->custName = $data['name'];
    $bookingService->custEmail = $data['email'];
    $bookingService->therapistEmail = $data['therapistEmail'];
    $bookingService->date = $data['date'];
    $bookingService->startTime = $data['startTime'];
    $bookingService->endTime = $data['endTime'];

    //add customer to stripe
    $bookingService->addCustomer();

    $paymentResponse = $bookingService->chargeAmount($bookingService->description, $stripeToken);
    // check whether the payment is successful
    if ($paymentResponse['amount_refunded'] == 0 && empty($paymentResponse['failure_code']) && $paymentResponse['paid'] == 1 && $paymentResponse['captured'] == 1) {
        // transaction details 

        $charge = $paymentResponse['id'];
        $amountPaid = $paymentResponse['amount'];
        $balanceTransaction = $paymentResponse['balance_transaction'];
        $paidCurrency = $paymentResponse['currency'];
        $paymentStatus = $paymentResponse['status'];
        //if order inserted successfully
        if ($paymentStatus == 'succeeded') {
            $bookingID = $bookingService->storePaymentDetailsInDB($amountPaid, $charge);
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = $bookingID;
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
} else if (strcmp($lvl, "2") == 0) {
    $bookingService = new BookingService($db);
    $email = $session->getEmail();
    
    if(empty($email)) {
        $email = $data['email'];
    }
    echo json_encode($bookingService->getAllBookings($email));
} else if (strcmp($lvl, "3") == 0) {
    $bookingID = $data['bookingID'];
    $bookingService = new BookingService($db);
    echo json_encode($bookingService->getBookingDetail($bookingID));
} else if (strcmp($lvl, "4") == 0) {
    $bookingID = $data['bookingID'];
    $bookingService = BookingService::withStripeToken('sk_test_51DIxM0IIBrLGUPv5nCv5PUA5p8Ahs3MY5ZzGcSyolhlHFo5I71fUITJvl40kZNkpCFQKMCE6zZ7reyZrLvHFNeHa009tzRII22', $db);
    $paymentCharge = $bookingService->getPaymentCharge($bookingID);
    $refundResponse = $bookingService->refundAmount($paymentCharge);
    $paymentStatus = $refundResponse['status'];
    if ($paymentStatus == 'succeeded') {
        echo json_encode($bookingService->updateBookingStatus($bookingID, "Cancelled"));
    } else {
        $response = new Response();
        $response->code = ResponseCodes::FAILURE;
        $response->desc = "Payment Refund Failed.";
        echo json_encode($response);
    }
} else if (strcmp($lvl, "5") == 0) {
    $amount = $data['amount'];
    $token = $data['token'];

    //check if stripe token exist to proceed with payment
    $bookingService = BookingService::withStripeToken('sk_test_51DIxM0IIBrLGUPv5nCv5PUA5p8Ahs3MY5ZzGcSyolhlHFo5I71fUITJvl40kZNkpCFQKMCE6zZ7reyZrLvHFNeHa009tzRII22', $db);

    //add customer to stripe
    $bookingService->addCustomer();

    $paymentResponse = $bookingService->chargeAmount($bookingService->description, $token);
    // check whether the payment is successful
    if ($paymentResponse['amount_refunded'] == 0 && empty($paymentResponse['failure_code']) && $paymentResponse['paid'] == 1 && $paymentResponse['captured'] == 1) {
        $paymentStatus = $paymentResponse['status'];
        if ($paymentStatus == 'succeeded') {
            echo json_encode($bookingService->checkWallet($amount, $session->getEmail()));
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Payment Failed.";
            echo json_encode($response);
        }
    }
} else if (strcmp($lvl, "6") == 0) {
    $bookingService = new BookingService($db);
    $email = $session->getEmail();
    
    if(empty($email)) {
        $email = $data['email'];
    }
    echo $bookingService->getWalletAmount($email);
} else if (strcmp($lvl, "7") == 0) {
    $bookingService = new BookingService($db);
    $bookingService->description  = $data['desc'];
    $bookingService->custEmail = $session->getEmail();

    if(empty($bookingService->custEmail)) {
        $bookingService->custEmail = $data['email'];
    }

    $bookingService->therapistEmail = $data['therapistEmail'];
    $bookingService->date = $data['date'];
    $bookingService->startTime = $data['startTime'];
    $bookingService->endTime = $data['endTime'];
    $bookingID = $bookingService->storeWalletPaymentDetailsInDB("50", "");
    $amount =  $bookingService->getWalletAmount($session->getEmail());
    $amount = (int)$amount - 50;
    $bookingService->updateWallet($amount, $bookingService->custEmail);
    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = $bookingID;
    echo json_encode($response);
} else if (strcmp($lvl, "8") == 0) {
    $bookingID = $data['bookingID'];
    $bookingService = new BookingService($db);
    $amount =  $bookingService->getWalletAmount($session->getEmail());
    $amount = (int)$amount + 50;
    echo json_encode($bookingService->refundWallet($amount, $session->getEmail(), $bookingID));
} else if (strcmp($lvl, "9") == 0) {
    $bookingService = new BookingService($db);
    
    $email = $session->getEmail();
    
    if(empty($email)) {
        $email = $data['email'];
    }
    
    echo json_encode($bookingService->getAllPatients($email));
} else if (strcmp($lvl, "10") == 0) {
    $bookingService = new BookingService($db);

    $email = $session->getEmail();
    
    if(empty($email)) {
        $email = $data['email'];
    }
    
    echo json_encode($bookingService->getRecentVisits($email));
} else if (strcmp($lvl, "11") == 0) {
    $bookingID = $data['bookingID'];
    $therapistEmail = $data['therapistEmail'];
    $bookingService = new BookingService($db);
    
    $email = $session->getEmail();
    
    if(empty($email)) {
        $email = $data['email'];
    }
    
    echo json_encode($bookingService->changeTherapist($bookingID, $therapistEmail));
} else if (strcmp($lvl, "12") == 0) {
    $bookingID = $data['bookingID'];
    $bookingService = new BookingService($db);
    echo json_encode($bookingService->updateBookingStatus($bookingID, "Finished"));
} else if (strcmp($lvl, "13") == 0) {
    $bookingID = $data['bookingID'];
    $bookingService = new BookingService($db);
    echo json_encode($bookingService->getFeedback($bookingID));
} else if (strcmp($lvl, "14") == 0) {
    $rating = $data['rating'];
    $review = $data['review'];
    $bookingID = $data['bookingID'];
    $bookingService = new BookingService($db);
    echo json_encode($bookingService->rateReview($bookingID, $rating, $review));
} else if (strcmp($lvl, "15") == 0) {
    $bookingService = new BookingService($db);
    
    $email = $session->getEmail();
    
    if(empty($email)) {
        $email = $data['email'];
    }
    
    echo json_encode($bookingService->getAllSessions($email));
} else if (strcmp($lvl, "16") == 0) {
    $bookingService = new BookingService($db);

    $email = $session->getEmail();
    
    if(empty($email)) {
        $email = $data['email'];
    }
    
    echo json_encode($bookingService->getActiveSessionsGroup($email));
} else if (strcmp($lvl, "17") == 0) {
    $therapistEmail = $data['therapistEmail'];
    $bookingService = new BookingService($db);
    $email = $session->getEmail();
    
    if(empty($email)) {
        $email = $data['email'];
    }
    
    echo json_encode($bookingService->getActiveSessions($email));
}
