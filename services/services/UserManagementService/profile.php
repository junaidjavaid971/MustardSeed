<?php

include_once '../models/ResponseModels/response.php';
include_once '../models/UserModels/ResetPasswordResponse.php';
include_once '../models/UserModels/usermodel.php';
include_once '../models/UserModels/userslist.php';
include_once '../models/AccountStatementModels/statementList.php';
include_once '../models/AccountStatementModels/statementModel.php';
require_once 'phpmailer/PHPMailerAutoload.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

class Profile
{

    // Connection
    private $conn;

    // Table
    private $userTable = "user";
    private $profileTable = "user_profile";
    private $servicesTable = "services";
    private $nominationsTable = "nominees";
    private $dbsValidationTable = "dbs_validation";
    private $membershipTable = "membership";
    private $nextOfKinTable = "next_of_kin";
    private $paymentTable = "payment";
    private $bookingTable = "bookings";
    private $regVerficiationCodeTable = "registrationverificationcodes";

    // Columns
    public $id;
    public $name;
    public $email;
    public $role;
    public $contactNumber;
    public $address;
    public $country;
    public $state;
    public $city;
    public $zipCode;
    public $accreditation;
    public $qualification;
    public $college;
    public $passingYear;
    public $membership;
    public $supervisorContactNumber;
    public $supervisorAddress;
    public $supervisorCountry;
    public $supervisorState;
    public $supervisorCity;
    public $organizationName;
    public $employeeDesignation;
    public $organizationMobileNumber;
    public $organizationContactNumber;
    public $organizationAddress;
    public $organizationCountry;
    public $organizationState;
    public $organizationCity;

    public $organizationEmail;
    public $organizationType;
    public $companyRegNo;
    public $directorName;
    public $numberOfYearsTrading;
    public $numberOfTherapists;
    public $numberOfManagers;
    public $isBusinessRegistered;
    public $ofstedNumber;
    public $sencoDetails;
    public $headTeacherDetails;
    public $charityNumber;
    public $responsibleTrustee;

    public $organizationZipCode;
    public $supervisorZipCode;
    public $isClientSupervisor;
    public $termsAccepted;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUsers()
    {
        $sqlQuery = "SELECT * FROM " . $this->profileTable . " WHERE
                    is_client_supervisor =:is_client_supervisor";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->isClientSupervisor = "true";
        $stmt->bindParam(":is_client_supervisor", $this->isClientSupervisor);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new UserModel();
            $user->id = $row['id'];
            $user->name = $row['name'];
            $user->email = $row['email'];
            $user->role = $row['role'];
            $user->contactNumber = $row['contact_number'];
            $user->address = $row['address'];
            $user->country = $row['country'];
            $user->state = $row['state'];
            $user->city = $row['city'];
            $user->zipCode = $row['zip_code'];
            $user->accreditation = $row['accreditation'];
            $user->qualification = $row['qualification'];
            $user->college = $row['college'];
            $user->passingYear = $row['passing_year'];
            $user->supervisorContactNumber = $row['supervisor_contact_number'];
            $user->supervisorAddress = $row['supervisor_address'];
            $user->supervisorCountry = $row['supervisor_country'];
            $user->supervisorState = $row['supervisor_state'];
            $user->supervisorCity = $row['supervisor_city'];
            $user->supervisorZipCode = $row['supervisor_zip_code'];
            $user->isClientSupervisor = $row['is_client_supervisor'];
            $user->termsAccepted = $row['terms_accepted'];
            array_push($response, $user);
        }
        $users = new Users();
        $users->user = $response;
        return $users;
    }

    // CREATE
    public function saveUserProfile($email)
    {
        $storeProfileQuery = "INSERT INTO 
                        " . $this->profileTable . "
                    SET 
                    name = :name, 
                    email = :email, 
                    role = :role, 
                    contact_number = :contact_number,
                    address = :address,
                    country = :country,
                    state = :state,
                    city = :city,
                    membership = :membership,
                    zip_code = :zip_code,
                    accreditation = :accreditation,
                    qualification = :qualification,
                    college = :college,
                    passing_year = :passing_year,
                    supervisor_contact_number = :supervisor_contact_number,
                    supervisor_address = :supervisor_address,
                    supervisor_country = :supervisor_country,
                    supervisor_state = :supervisor_state,
                    supervisor_city = :supervisor_city,
                    supervisor_zip_code = :supervisor_zip_code,
                    organization_name = :organization_name,
                    employee_designation = :employee_designation,
                    organization_contact_number = :organization_contact_number,
                    organization_address = :organization_address,
                    organization_country = :organization_country,
                    organization_state = :organization_state,
                    organization_city = :organization_city,
                    organization_zip_code = :organization_zip_code,
                    organization_email = :organization_email,
                    organization_type = :organization_type,
                    company_reg_no = :company_reg_no,
                    director_name = :director_name,
                    no_of_years_trading = :no_of_years_trading,
                    no_of_therapists = :no_of_therapists,
                    no_of_managers = :no_of_managers,
                    is_business_registered = :is_business_registered,
                    ofsted_number = :ofsted_number,
                    senco_details = :senco_details,
                    head_teacher_details = :head_teacher_details,
                    charity_number = :charity_number,
                    responsible_trustee = :responsible_trustee,
                    is_client_supervisor = :is_client_supervisor,
                    terms_accepted = :terms_accepted";

        $stmt = $this->conn->prepare($storeProfileQuery);
        // sanitize
        $this->name = $this->getProfileName($email);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->contactNumber = htmlspecialchars(strip_tags($this->contactNumber));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->country = htmlspecialchars(strip_tags($this->country));
        $this->state = htmlspecialchars(strip_tags($this->state));
        $this->zipCode = htmlspecialchars(strip_tags($this->zipCode));
        $this->accreditation = htmlspecialchars(strip_tags($this->accreditation));
        $this->qualification = htmlspecialchars(strip_tags($this->qualification));
        $this->college = htmlspecialchars(strip_tags($this->college));
        $this->passingYear = htmlspecialchars(strip_tags($this->passingYear));

        $this->organizationName = htmlspecialchars(strip_tags($this->organizationName));
        $this->employeeDesignation = htmlspecialchars(strip_tags($this->employeeDesignation));
        $this->organizationAddress = htmlspecialchars(strip_tags($this->organizationAddress));
        $this->organizationCountry = htmlspecialchars(strip_tags($this->organizationCountry));
        $this->organizationState = htmlspecialchars(strip_tags($this->organizationState));
        $this->organizationCity = htmlspecialchars(strip_tags($this->organizationCity));
        $this->organizationZipCode = htmlspecialchars(strip_tags($this->organizationZipCode));

        $this->organizationEmail = htmlspecialchars(strip_tags($this->organizationEmail));
        $this->organizationType = htmlspecialchars(strip_tags($this->organizationType));
        $this->companyRegNo = htmlspecialchars(strip_tags($this->companyRegNo));
        $this->directorName = htmlspecialchars(strip_tags($this->directorName));
        $this->numberOfYearsTrading = htmlspecialchars(strip_tags($this->numberOfYearsTrading));
        $this->numberOfTherapists = htmlspecialchars(strip_tags($this->numberOfTherapists));
        $this->numberOfManagers = htmlspecialchars(strip_tags($this->numberOfManagers));
        $this->isBusinessRegistered = htmlspecialchars(strip_tags($this->isBusinessRegistered));
        $this->ofstedNumber = htmlspecialchars(strip_tags($this->ofstedNumber));
        $this->sencoDetails = htmlspecialchars(strip_tags($this->sencoDetails));
        $this->headTeacherDetails = htmlspecialchars(strip_tags($this->headTeacherDetails));
        $this->charityNumber = htmlspecialchars(strip_tags($this->charityNumber));
        $this->responsibleTrustee = htmlspecialchars(strip_tags($this->responsibleTrustee));

        $this->supervisorContactNumber = htmlspecialchars(strip_tags($this->supervisorContactNumber));
        $this->supervisorAddress = htmlspecialchars(strip_tags($this->supervisorAddress));
        $this->supervisorCountry = htmlspecialchars(strip_tags($this->supervisorCountry));
        $this->supervisorState = htmlspecialchars(strip_tags($this->supervisorState));
        $this->supervisorCity = htmlspecialchars(strip_tags($this->supervisorCity));
        $this->supervisorZipCode = htmlspecialchars(strip_tags($this->supervisorZipCode));
        $this->isClientSupervisor = htmlspecialchars(strip_tags($this->isClientSupervisor));
        $this->termsAccepted = htmlspecialchars(strip_tags($this->termsAccepted));
        // bind data
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":contact_number", $this->contactNumber);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":state", $this->state);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":membership", $this->membership);
        $stmt->bindParam(":zip_code", $this->zipCode);
        $stmt->bindParam(":accreditation", $this->accreditation);
        $stmt->bindParam(":qualification", $this->qualification);
        $stmt->bindParam(":college", $this->college);
        $stmt->bindParam(":passing_year", $this->passingYear);
        $stmt->bindParam(":organization_name", $this->organizationName);
        $stmt->bindParam(":employee_designation", $this->employeeDesignation);
        $stmt->bindParam(":organization_contact_number", $this->organizationContactNumber);
        $stmt->bindParam(":organization_address", $this->organizationAddress);
        $stmt->bindParam(":organization_country", $this->organizationCountry);
        $stmt->bindParam(":organization_state", $this->organizationState);
        $stmt->bindParam(":organization_city", $this->organizationCity);
        $stmt->bindParam(":organization_zip_code", $this->organizationZipCode);
        $stmt->bindParam(":organization_email", $this->organizationEmail);
        $stmt->bindParam(":organization_type", $this->organizationType);
        $stmt->bindParam(":company_reg_no", $this->companyRegNo);
        $stmt->bindParam(":director_name", $this->directorName);
        $stmt->bindParam(":no_of_years_trading", $this->numberOfYearsTrading);
        $stmt->bindParam(":no_of_therapists", $this->numberOfTherapists);
        $stmt->bindParam(":no_of_managers", $this->numberOfManagers);
        $stmt->bindParam(":is_business_registered", $this->isBusinessRegistered);
        $stmt->bindParam(":ofsted_number", $this->ofstedNumber);
        $stmt->bindParam(":senco_details", $this->sencoDetails);
        $stmt->bindParam(":head_teacher_details", $this->headTeacherDetails);
        $stmt->bindParam(":charity_number", $this->charityNumber);
        $stmt->bindParam(":responsible_trustee", $this->responsibleTrustee);
        $stmt->bindParam(":supervisor_contact_number", $this->supervisorContactNumber);
        $stmt->bindParam(":supervisor_address", $this->supervisorAddress);
        $stmt->bindParam(":supervisor_country", $this->supervisorCountry);
        $stmt->bindParam(":supervisor_state", $this->supervisorState);
        $stmt->bindParam(":supervisor_city", $this->supervisorCity);
        $stmt->bindParam(":supervisor_zip_code", $this->supervisorZipCode);
        $stmt->bindParam(":is_client_supervisor", $this->isClientSupervisor);
        $stmt->bindParam(":terms_accepted", $this->termsAccepted);

        if (!$stmt->execute()) {
            echo $stmt->error;
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Unexpected error occured while storing the profile data. Please try again!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Your profile information has saved successfully.";
            return $response;
        }
    }

    public function nominateSupervisor($email, $nominatedSupervisorEmail)
    {
        $nominateSupervisorQuery = "INSERT INTO 
                        " . $this->nominationsTable . "
                    SET 
                    user_email = :user_email,
                    supervisor_email = :supervisor_email,
                    nomination_accepted = :nomination_accepted";

        $stmt = $this->conn->prepare($nominateSupervisorQuery);
        // sanitize
        $nominatedSupervisorEmail = htmlspecialchars(strip_tags($nominatedSupervisorEmail));
        $nominationAccepted = false;
        // bind data
        $stmt->bindParam(":user_email", $email);
        $stmt->bindParam(":supervisor_email", $nominatedSupervisorEmail);
        $stmt->bindParam(":nomination_accepted", $nominationAccepted);
        try {
            if (!$stmt->execute()) {
                $response = new Response();
                $response->code = ResponseCodes::FAILURE;
                $response->desc = "Unexpected error occured while nominating the supervisor. Please try again!";
                return $response;
            } else {
                return $this->sendNominationRequestEmail($email, $nominatedSupervisorEmail);
            }
        } catch (\PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
        }
    }

    function sendNominationRequestEmail($email, $nominatedSupervisorEmail)
    {
        $mail = new PHPMailer;

        $mail->isSMTP();
        //Set SMTP host name                          
        $mail->Host = "mail.gandi.net";
        //Set TCP port to connect to
        $mail->Port = 587;
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "STARTTLS";
        //Provide username and password     
        $mail->Username = "noreply@mustardseed.care";
        $mail->Password = "Q@werty@8102@";
        $mail->setFrom('noreply@mustardseed.care', 'Mustard Seed');
        $mail->addAddress($nominatedSupervisorEmail);

        $mail->Subject = "Mustard Seed - Nomination Request";
        $url = file_get_contents("http://localhost/MustardSeed/nomination-email.html");
        $content = str_replace(
            array('%name%', '%nominator%', '%url%'),
            array($this->getUsername($nominatedSupervisorEmail), $this->$nominatedSupervisorEmail, 'https://mustardseed.care'),
            $url
        );
        $mail->Body = $content;
        $mail->isHTML(true);

        if ($mail->send()) {
            $response = new ResetPasswordResponse();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Nomination request sent to the supervisor!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Email Error: " . $mail->ErrorInfo;
            return $response;
        }
    }

    private function getUsername($email)
    {
        $username = substr($email, 0, strrpos($email, '@'));
        return $username;
    }

    public function requestDbsValidation($email)
    {
        $dbsValidationQuery = "INSERT INTO 
                        " . $this->dbsValidationTable . "
                    SET
                    user_email = :user_email,
                    validation_status = :validation_status";

        $stmt = $this->conn->prepare($dbsValidationQuery);
        $validationStatus = "Requested";
        // bind data
        $stmt->bindParam(":user_email", $email);
        $stmt->bindParam(":validation_status", $validationStatus);

        if (!$stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Unexpected error occured while requesting the dbs certificate validation. Please try again!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "DBS Certificate Validation Requested!";
            return $response;
        }
    }

    public function requestMembership($email)
    {
        $dbsValidationQuery = "INSERT INTO 
                        " . $this->membershipTable . "
                    SET
                    user_email = :user_email,
                    membership_status = :membership_status";

        $stmt = $this->conn->prepare($dbsValidationQuery);
        $membershipStatus = "Requested";
        // bind data
        $stmt->bindParam(":user_email", $email);
        $stmt->bindParam(":membership_status", $membershipStatus);

        if (!$stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Unexpected error occured while requesting the membership. Please try again!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Membership Requested!";
            return $response;
        }
    }

    public function getNominatedSupervisor($email)
    {
        $userID = $this->getUserID($email, "1");
        $sqlQuery = "SELECT user_profile.* FROM " . $this->profileTable . " INNER JOIN "
            . $this->nominationsTable . " ON user_profile.email = nominees.supervisor_email
                    WHERE
                    nominees.user_email = :user_email";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam("user_email", $email);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = new UserModel();
        $user->id = $row['id'];
        $user->name = $row['name'];
        $user->email = $row['email'];
        $user->role = $row['role'];
        $user->contactNumber = $row['contact_number'];
        $user->address = $row['address'];
        $user->country = $row['country'];
        $user->membership = $row['membership'];
        $user->state = $row['state'];
        $user->city = $row['city'];
        $user->zipCode = $row['zip_code'];
        $user->accreditation = $row['accreditation'];
        $user->qualification = $row['qualification'];
        $user->college = $row['college'];
        $user->passingYear = $row['passing_year'];
        $user->supervisorContactNumber = $row['supervisor_contact_number'];
        $user->supervisorAddress = $row['supervisor_address'];
        $user->supervisorCountry = $row['supervisor_country'];
        $user->supervisorState = $row['supervisor_state'];
        $user->supervisorCity = $row['supervisor_city'];
        $user->supervisorZipCode = $row['supervisor_zip_code'];
        $user->isClientSupervisor = $row['is_client_supervisor'];
        $user->termsAccepted = $row['terms_accepted'];

        return $user;
    }

    private function getUserID($email, $act)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->profileTable . "
                    WHERE 
                       email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $email);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (strcmp($act, "1") == 0) {
            return $row['id'];
        } else {
            return $row['name'];
        }
    }

    private function getDbsValidationStatus($email)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->dbsValidationTable . "
                    WHERE 
                       user_email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $email);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'];
    }

    private function getMembershipStatus($email)
    {
        $sqlQuery = "SELECT
                        membership
                      FROM
                        " . $this->profileTable . "
                    WHERE 
                       email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $email);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['membership'];
    }

    public function saveProfilePicture($base64, $email)
    {
        if (!file_exists('../images/profile_pictures')) {
            mkdir('../images/profile_pictures', 0777, true);
        }
        $fp = fopen("../images/profile_pictures/" . $email . ".png", "w+");

        // write the data in image file
        fwrite($fp, base64_decode($base64));

        // close an open file pointer
        fclose($fp);

        $response = new Response();
        $response->code = ResponseCodes::SUCCESS;
        $response->desc = "Image Saved!";
        return $response;
    }

    public function uploadCV($base64, $email)
    {
        if (!file_exists('../files/docs/cv')) {
            mkdir('../files/docs/cv', 0777, true);
        }
        $fp = fopen("../files/docs/cv/" . $email . ".pdf", "w+");

        // write the data in image file
        fwrite($fp, base64_decode($base64));

        // close an open file pointer
        fclose($fp);

        $response = new Response();
        $response->code = ResponseCodes::SUCCESS;
        $response->desc = "CV Uploaded!";
        return $response;
    }

    // UPDATE
    public function getProfile($email)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->profileTable . "
                    WHERE 
                       email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $email);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = new UserModel();
        $user->id = $row['id'];
        $user->name = $row['name'];
        $user->email = $row['email'];
        $user->role = $row['role'];
        $user->contactNumber = $row['contact_number'];
        $user->membership = $row['membership'];
        $user->address = $row['address'];
        $user->country = $row['country'];
        $user->state = $row['state'];
        $user->city = $row['city'];
        $user->zipCode = $row['zip_code'];
        $user->accreditation = $row['accreditation'];
        $user->qualification = $row['qualification'];
        $user->college = $row['college'];
        $user->passingYear = $row['passing_year'];
        $user->supervisorContactNumber = $row['supervisor_contact_number'];
        $user->supervisorAddress = $row['supervisor_address'];
        $user->supervisorCountry = $row['supervisor_country'];
        $user->supervisorState = $row['supervisor_state'];
        $user->supervisorCity = $row['supervisor_city'];
        $user->supervisorZipCode = $row['supervisor_zip_code'];
        $user->isClientSupervisor = $row['is_client_supervisor'];
        $user->termsAccepted = $row['terms_accepted'];

        return $user;
    }

    public function checkUserProfileStatus($email)
    {
        $stage = 0;
        $user = $this->getNominatedSupervisor($email);
        if ($user->id == null) {
            return $stage;
        } else if (empty($this->getDbsValidationStatus($email))) {
            $stage = 1;
            return $stage;
        } else if (empty($this->getMembershipStatus($email))) {
            $stage = 2;
            return $stage;
        } else {
            $stage = 3;
            return $stage;
        }
    }

    function sendInvitationEmail($invitedEmail)
    {
        $mail = new PHPMailer;

        $mail->isSMTP();
        //Set SMTP host name                          
        $mail->Host = "smtp.gmail.com";
        //Set TCP port to connect to
        $mail->Port = 587;
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "tls";
        //Provide username and password     
        $mail->Username = "junaidjavaid971@gmail.com";
        $mail->Password = "jipbbzvmwiechlko";
        $mail->setFrom('junaidjavaid971@gmail.com', 'Mustard Seed');
        $mail->addAddress($invitedEmail);
        $mail->isHTML(true);
        $mail->Subject = "Invitation Mustard Seed";
        $mail->Body = "You have received an invitation request to join Mustard Seed. To become a part of MS, please go through the below link: <br />
                            <a href='http://localhost/MustardSeed/registration.html'>Register Now</a>";

        if ($mail->send()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Invitation Sent!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Unexpected error occured in sending the invitation email!";
            return $response;
        }
    }
    // GET ALL THERAPISTS
    public function getAllTherapists()
    {
        $sqlQuery = "SELECT * FROM " . $this->profileTable . " WHERE role = :role";

        $stmt = $this->conn->prepare($sqlQuery);
        $role = "Therapist";
        $stmt->bindParam(":role", $role);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new UserModel();
            $user->id = $row['id'];
            $user->name = $row['name'];
            $user->email = $row['email'];
            $user->role = $row['role'];
            $user->contactNumber = $row['contact_number'];
            $user->address = $row['address'];
            $user->country = $row['country'];
            $user->state = $row['state'];
            $user->city = $row['city'];
            $user->zipCode = $row['zip_code'];
            $user->accreditation = $row['accreditation'];
            $user->qualification = $row['qualification'];
            $user->college = $row['college'];
            $user->passingYear = $row['passing_year'];
            $user->supervisorContactNumber = $row['supervisor_contact_number'];
            $user->supervisorAddress = $row['supervisor_address'];
            $user->supervisorCountry = $row['supervisor_country'];
            $user->supervisorState = $row['supervisor_state'];
            $user->supervisorCity = $row['supervisor_city'];
            $user->supervisorZipCode = $row['supervisor_zip_code'];
            $user->isClientSupervisor = $row['is_client_supervisor'];
            array_push($response, $user);
        }
        $users = new UsersList();
        $users->users = $response;
        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Therapists List";
        $jsonResponse->data = $users;
        return $jsonResponse;
    }

    // GET ALL THERAPISTS
    public function getSearchedTherapist($query)
    {
        $sqlQuery = "SELECT * FROM " . $this->profileTable . " WHERE role = :role AND name LIKE :pattern";

        $stmt = $this->conn->prepare($sqlQuery);
        $role = "Therapist";
        $pattern = '%' . $query . '%';
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":pattern", $pattern);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = new UserModel();
            $user->id = $row['id'];
            $user->name = $row['name'];
            $user->email = $row['email'];
            $user->role = $row['role'];
            $user->contactNumber = $row['contact_number'];
            $user->address = $row['address'];
            $user->country = $row['country'];
            $user->state = $row['state'];
            $user->city = $row['city'];
            $user->zipCode = $row['zip_code'];
            $user->accreditation = $row['accreditation'];
            $user->qualification = $row['qualification'];
            $user->college = $row['college'];
            $user->passingYear = $row['passing_year'];
            $user->supervisorContactNumber = $row['supervisor_contact_number'];
            $user->supervisorAddress = $row['supervisor_address'];
            $user->supervisorCountry = $row['supervisor_country'];
            $user->supervisorState = $row['supervisor_state'];
            $user->supervisorCity = $row['supervisor_city'];
            $user->supervisorZipCode = $row['supervisor_zip_code'];
            $user->isClientSupervisor = $row['is_client_supervisor'];
            array_push($response, $user);
        }
        $users = new UsersList();
        $users->users = $response;
        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Therapists List";
        $jsonResponse->data = $users;
        return $jsonResponse;
    }

    // GET ALL THERAPISTS
    public function getSearchedService($query)
    {
        $sqlQuery = "SELECT * FROM " . $this->servicesTable . " WHERE service_title LIKE :pattern";

        $stmt = $this->conn->prepare($sqlQuery);
        $pattern = '%' . $query . '%';
        $stmt->bindParam(":pattern", $pattern);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $service = new ServicesModal();
            $service->id = $row['id'];
            $service->serviceTitle = $row['service_title'];
            $service->serviceDesc = $row['service_desc'];
            $service->serviceType = $row['service_type'];
            $service->serviceDuration = $row['service_duration'];
            $service->serviceCost = $row['service_cost'];
            $service->email = $row['user_email'];

            array_push($response, $service);
        }
        $services = new ServicesList();
        $services->services = $response;
        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Services List";
        $jsonResponse->data = $services;
        return $jsonResponse;
    }

    public function getAccountStatement($email)
    {
        $sqlQuery = "SELECT bookings.booking_status, bookings.booking_date, bookings.therapist_email,
                             payment.payment_date, payment.amount, 
                             user_profile.name, user_profile.email 
            FROM " . $this->bookingTable . " INNER JOIN "
            . $this->paymentTable . " ON bookings.payment_id = payment.p_id INNER JOIN "
            . $this->profileTable . " ON bookings.user_email = user_profile.email 
            WHERE
            bookings.user_email = :user_email";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam("user_email", $email);

        $stmt->execute();
        $response = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $statement = new StatementModel();
            $statement->userName = $row['name'];
            $statement->therapistName = $this->getTherapistName($row['therapist_email']);
            $statement->therapyDate = $row['booking_date'];
            $statement->bookingStatus = $row['booking_status'];
            $statement->cost = $row['amount'];
            array_push($response, $statement);
        }
        $statementList = new StatementList();
        $statementList->statements = $response;
        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Account Statement List";
        $jsonResponse->data = $statementList;
        return $jsonResponse;
    }

    public function getTherapistName($therapistEmail)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->profileTable . "
                    WHERE 
                       email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $therapistEmail);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['name'];
    }

    public function getProfileName($email)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->userTable . "
                    WHERE 
                       u_email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $email);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['u_name'];
    }

    function saveNextOfKin($email, $name)
    {
        $nextOfKinQuery = "INSERT INTO 
                        " . $this->nextOfKinTable . "
                    SET
                    name = :name,
                    email = :email,
                    contact_number = :contact_number,
                    user_email = :user_email";

        $stmt = $this->conn->prepare($nextOfKinQuery);
        // bind data
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":contact_number", $this->contactNumber);
        $stmt->bindParam(":user_email", $email);

        if (!$stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Unexpected error occured while saving the information. Please try again!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Next of kin details updated!";
            return $response;
        }
    }
    public function getNextOfKin($email)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->nextOfKinTable . "
                    WHERE 
                       user_email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $email);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Next of kin!";
            $response->data = $row;
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Next of kin!";
            return $response;
        }
    }

    function verifyRegistrationOTP($email, $otp)
    {
        $sqlQuery = "SELECT
                        *
                    FROM
                        " . $this->regVerficiationCodeTable . "
                    WHERE 
                        email = :email AND
                        code = :code AND
                        is_verified = :verify
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);
        $verify = 0;
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":code", $otp);
        $stmt->bindParam(":verify", $verify);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dataRow != null) {
            $deleteQuery = "DELETE from " . $this->regVerficiationCodeTable . " WHERE email = :email";
            $deleteSTMT = $this->conn->prepare($deleteQuery);
            $deleteSTMT->bindParam(":email", $email);
            $deleteSTMT->execute();

            $sqlQuery = "UPDATE
                " . $this->userTable . "
                SET 
                    is_verified = :is_verified
                WHERE 
                    u_email = :email";

            $stmt = $this->conn->prepare($sqlQuery);

            $isVerfied = 1;
            // bind data
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":is_verified", $isVerfied);

            if ($stmt->execute()) {
                $response = new Response();
                $response->code = ResponseCodes::SUCCESS;
                $response->desc = "Your account is verified!";
                return $response;
            } else {
                $response = new Response();
                $response->code = ResponseCodes::INVALID_PASSWORD_RESET_VERIFICATION_CODE;
                $response->desc = "Verification code does not match";
                return $response;
            }
        } else {
            $response = new Response();
            $response->code = ResponseCodes::INVALID_PASSWORD_RESET_VERIFICATION_CODE;
            $response->desc = "Verification code does not match";
            return $response;
        }
    }
}
