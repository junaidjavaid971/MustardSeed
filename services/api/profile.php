<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/sessions.php';
include_once '../services/UserManagementService/user.php';
include_once '../services/UserManagementService/services.php';
include_once '../services/UserManagementService/profile.php';
include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';


/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$database = new Database();
$db = $database->getConnection();

$profile = new Profile($db);
$session = new Session();

$data = $_POST['Data'];

if ($data == null) {
    $tempRes = json_decode(file_get_contents('php://input'), true);
    $data = $tempRes['Data'];
}

$lvl = $data['lvl'];

if (strcmp($lvl, "1") == 0) {
    $profile->name = $data['name'];
    $profile->email = $data['email'];
    $profile->role = $data['role'];
    $profile->contactNumber = $data['contactNumber'];
    $profile->address = $data['address'];
    $profile->country = $data['country'];
    $profile->state = $data['state'];
    $profile->city = $data['city'];
    $profile->zipCode = $data['zipcode'];
    $profile->accreditation = $data['accreditation'];
    $profile->qualification = $data['qualification'];
    $profile->college = $data['college'];
    $profile->passingYear = $data['passingYear'];
    $profile->membership = $data['membership'];
    $profile->organizationName = $data['organizationName'];
    $profile->employeeDesignation = $data['employeeDesignation'];
    $profile->organizationContactNumber = $data['organizationContactNumber'];
    $profile->organizationAddress = $data['organizationAddress'];
    $profile->organizationCountry = $data['organizationCountry'];
    $profile->organizationState = $data['organizationState'];
    $profile->organizationCity = $data['organizationCity'];
    $profile->organizationZipCode = $data['organizationZipCode'];
    $profile->organizationEmail = $data['organizationEmail'];
    $profile->organizationType = $data['organizationType'];
    $profile->companyRegNo = $data['companyRegNo'];
    $profile->directorName = $data['directorName'];
    $profile->numberOfYearsTrading = $data['numberOfYearsTrading'];
    $profile->numberOfTherapists = $data['numberOfTherapists'];
    $profile->numberOfManagers = $data['numberOfManagers'];
    $profile->isBusinessRegistered = $data['isBusinessRegistered'];
    $profile->ofstedNumber = $data['ofstedNumber'];
    $profile->sencoDetails = $data['sencoDetails'];
    $profile->headTeacherDetails = $data['headTeacherDetails'];
    $profile->charityNumber = $data['charityNumber'];
    $profile->responsibleTrustee = $data['responsibleTrustee'];
    $profile->supervisorContactNumber = $data['supervisorContactNumber'];
    $profile->supervisorAddress = $data['supervisorAddress'];
    $profile->supervisorCountry = $data['supervisorCountry'];
    $profile->supervisorState = $data['supervisorState'];
    $profile->supervisorCity = $data['supervisorCity'];
    $profile->supervisorZipCode = $data['supervisorZipCode'];
    $profile->isClientSupervisor = $data['clientSuperviser'];
    $profile->termsAccepted = $data['terms'];

    $email = $session->getEmail();

    if (empty($email)) {
        $email = $data['email'];
    }

    $response = $profile->saveUserProfile($email);
    echo json_encode($response);
} else if (strcmp($lvl, "2") == 0) {
    $base64 = $data['img'];
    $email = $data['email'];
    echo json_encode($profile->saveProfilePicture($base64, $email));
} else if (strcmp($lvl, "3") == 0) {
    $base64 = $data['base64'];
    $email = $data['email'];
    echo json_encode($profile->uploadCV($base64, $email));
} else if (strcmp($lvl, "4") == 0) {
    $response = new Response();
    $response->code = "00";
    $response->desc = "Supervisors List";
    $response->data = $profile->getUsers();
    echo json_encode($response);
} else if (strcmp($lvl, "5") == 0) {
    $email = $data['email'];
    $response = new Response();
    $response->code = "00";
    $response->desc = "Profile";
    $response->data = $profile->getProfile($email);
    echo json_encode($response);
} else if (strcmp($lvl, "6") == 0) {
    $email = $data['userEmail'];
    $nominatedSupervisorEmail = $data['supervisorEmail'];
    $response = new Response();
    $response->code = "00";
    $response->desc = "Profile";
    $response->data = $profile->nominateSupervisor($email, $nominatedSupervisorEmail);
    echo json_encode($response);
} else if (strcmp($lvl, "7") == 0) {
    $email = $session->getEmail();
    $response = new Response();
    $response->code = "00";
    $response->desc = "";
    $response->data = $profile->getNominatedSupervisor($email);
    echo json_encode($response);
} else if (strcmp($lvl, "8") == 0) {
    $email = $data['email'];
    echo json_encode($profile->requestDbsValidation($email));
} else if (strcmp($lvl, "9") == 0) {
    $email = $data['email'];
    echo json_encode($profile->requestMembership($email));
} else if (strcmp($lvl, "10") == 0) {
    $email = $data['email'];
    echo json_encode($profile->checkUserProfileStatus($email));
} else if (strcmp($lvl, "11") == 0) {
    $invitedEmail = $data['inviteeEmail'];
    echo json_encode($profile->sendInvitationEmail($invitedEmail));
} else if (strcmp($lvl, "12") == 0) {
    echo json_encode($profile->getAllTherapists());
} else if (strcmp($lvl, "13") == 0) {
    $email = $session->getEmail();

    if (empty($email)) {
        $email = $data['email'];
    }

    echo json_encode($profile->getAccountStatement($email));
} else if (strcmp($lvl, "14") == 0) {
    $name = $data['kinName'];
    $profile->email = $data['kinEmail'];
    $profile->contactNumber = $data['kinContact'];
    $email = $session->getEmail();

    if (empty($email)) {
        $email = $data['email'];
    }

    echo json_encode($profile->saveNextOfKin($email, $name));
} else if (strcmp($lvl, "15") == 0) {
    $email = $session->getEmail();
    if (empty($email)) {
        $email = $data['email'];
    }
    echo json_encode($profile->getNextOfKin($email));
} else if (strcmp($lvl, "16") == 0) {
    $query = $data['query'];
    echo json_encode($profile->getSearchedTherapist($query));
} else if (strcmp($lvl, "17") == 0) {
    $email = $session->getEmail();

    if (empty($email)) {
        $email = $data['email'];
    }

    $user = $profile->getProfile($email);

    $response = new Response();
    $response->code = "00";
    $response->desc = "Profile";
    $response->data = $user->role;
    echo json_encode($response);
} else if (strcmp($lvl, "18") == 0) {
    $otp = $data['verCode'];
    $email = $data['email'];
    echo json_encode($profile->verifyRegistrationOTP($email, $otp));
} else if (strcmp($lvl, "19") == 0) {
    $service = new Services($db);

    $service->serviceTitle = $data['serviceTitle'];
    $service->serviceDesc = $data['serviceDesc'];
    $service->serviceDuration = $data['serviceDuration'];
    $service->serviceCost = $data['serviceCost'];
    $service->serviceType = $data['serviceType'];

    $service->email = $session->getEmail();
    if (empty($email)) {
        $email = $data['email'];
    }

    echo json_encode($service->addService());
} else if (strcmp($lvl, "20") == 0) {
    $service = new Services($db);
    $email = $session->getEmail();
    
    if (empty($email)) {
        $email = $data['email'];
    }

    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "Service List!";
    $response->data = $service->getServices();

    echo json_encode($response);
} else if (strcmp($lvl, "21") == 0) {
    $service = new Services($db);
    $service->id = $data['serviceID'];

    $response = new Response();
    $response->code = ResponseCodes::SUCCESS;
    $response->desc = "Service List!";
    $response->data = $service->getServiceOnID();

    echo json_encode($response);
} else if (strcmp($lvl, "22") == 0) {
    $query = $data['query'];
    echo json_encode($profile->getSearchedService($query));
}
