<?php

include_once '../models/ResponseModels/response.php';
include_once '../models/ResponseModels/ResetPasswordResponse.php';
include_once '../models/nominees/nominees.php';
include_once '../models/nominees/nomineeslist.php';
include_once '../models/supervision/supervisionReport.php';
include_once '../models/supervision/supervisionReportsList.php';
require 'phpmailer/PHPMailerAutoload.php';

class Supervisor
{
    // Connection
    private $conn;

    private $supervisionTable = "supervision";
    private $nominationsTable = "nominees";
    private $user_profile = "user_profile";

    //Supervision Data Variables
    public $userEmail;
    public $supervisorEmail;
    public $contactName;
    public $contactDetails;
    public $contactTime;
    public $contactDate;
    public $attachmentUrl;
    public $attachmentExt;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getNomination($email)
    {
        $sqlQuery = "SELECT * FROM " . $this->nominationsTable . " INNER JOIN "
            . $this->user_profile . " ON nominees.supervisor_email = user_profile.email WHERE nominees.user_email = :email group by nominees.supervisor_email";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nominee = new NomineeModel();
            $nominee->id = $row['id'];
            $nominee->name = $row['name'];
            $nominee->email = $row['email'];
            $nominee->role = $row['role'];
            $nominee->contactNumber = $row['contact_number'];
            $nominee->address = $row['address'];
            $nominee->country = $row['country'];
            $nominee->state = $row['state'];
            $nominee->city = $row['city'];
            $nominee->zipCode = $row['zip_code'];
            $nominee->accreditation = $row['accreditation'];
            $nominee->qualification = $row['qualification'];
            $nominee->college = $row['college'];
            $nominee->passingYear = $row['passing_year'];
            $nominee->supervisorContactNumber = $row['supervisor_contact_number'];
            $nominee->supervisorAddress = $row['supervisor_address'];
            $nominee->supervisorCountry = $row['supervisor_country'];
            $nominee->supervisorState = $row['supervisor_state'];
            $nominee->supervisorCity = $row['supervisor_city'];
            $nominee->supervisorZipCode = $row['supervisor_zip_code'];
            $nominee->isClientSupervisor = $row['is_client_supervisor'];
            $nominee->termsAccepted = $row['terms_accepted'];
            array_push($response, $nominee);
        }
        $nominations = new NomineesList();
        $nominations->nominees = $response;
        return $nominations;
    }

    public function sendReportForSupervision()
    {
        $supervisionQuery = "INSERT INTO 
                        " . $this->supervisionTable . "
                    SET
                    user_email = :user_email,
                    supervisor_email = :supervisor_email,
                    contact_name = :contact_name,
                    contact_details = :contact_details,
                    contact_date = :contact_date,
                    contact_time = :contact_time,
                    is_document_attached = :is_document_attached,
                    attachment_extension = :attachment_extension";

        $stmt = $this->conn->prepare($supervisionQuery);
        // bind data
        $stmt->bindParam(":user_email", $this->userEmail);
        $stmt->bindParam(":supervisor_email", $this->supervisorEmail);
        $stmt->bindParam(":contact_name", $this->contactName);
        $stmt->bindParam(":contact_details", $this->contactDetails);
        $stmt->bindParam(":contact_date", $this->contactDate);
        $stmt->bindParam(":contact_time", $this->contactTime);
        $stmt->bindParam(":attachment_extension", $this->attachmentExt);
        if (strcmp($this->attachmentUrl, "") == 0) {
            $docAttached = false;
            $stmt->bindParam(":is_document_attached", $docAttached);
        } else {
            $docAttached = true;
            $stmt->bindParam(":is_document_attached", $docAttached);
        }

        if (!$stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Unexpected error occured while sending the supervision report. Please try again!";
            return $response;
        } else {
            if (strcmp($this->attachmentUrl, "") == 0) {
                $response = new Response();
                $response->code = ResponseCodes::SUCCESS;
                $response->desc = "Patient report sent to the supervisor for supervision!";
                return $response;
            } else {
                $this->saveAttachment($this->conn->lastInsertId());
                $response = new Response();
                $response->code = ResponseCodes::SUCCESS;
                $response->desc = "Patient report sent to the supervisor for supervision!";
                return $response;
            }
        }
    }

    public function saveAttachment($id)
    {
        $filePath = "../supervision_attachments/" . $this->userEmail . "/" . $this->supervisorEmail;
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
        $fp = fopen($filePath . "/" . $id . "." . $this->attachmentExt, "w+");

        // write the data in image file
        fwrite($fp, base64_decode($this->attachmentUrl));

        // close an open file pointer
        fclose($fp);
    }

    public function getSupervisionReports()
    {
        $sqlQuery = "SELECT * FROM " . $this->supervisionTable . " WHERE user_email=:email";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":email", $this->userEmail);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $supervisionReport = new SupervisionReport();
            $supervisionReport->id = $row['id'];
            $supervisionReport->userEmail = $row['user_email'];
            $supervisionReport->supervisorEmail = $row['supervisor_email'];
            $supervisionReport->contactName = $row['contact_name'];
            $supervisionReport->contactDetails = $row['contact_details'];
            $supervisionReport->contactDate = $row['contact_date'];
            $supervisionReport->contactTime = $row['contact_time'];
            $supervisionReport->isDocumentAttached = $row['is_document_attached'];
            $supervisionReport->attachmentExtension = $row['attachment_extension'];

            array_push($response, $supervisionReport);
        }
        $supervisionReports = new SupervisionList();
        $supervisionReports->supervisionReports = $response;
        return $supervisionReports;
    }
}
