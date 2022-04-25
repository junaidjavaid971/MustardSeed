<?php

use Stripe\Customer;

require '../../stripe/init.php';
include_once '../models/response.php';
include_once '../enums/responsecodes.php';
include_once '../models/ContractModels/contractModel.php';
include_once '../models/ContractModels/contractList.php';
include_once '../models/ContractModels/ContractApplication.php';


class ContractService
{
    private $conn;
    private $contractTable = "contract";
    private $contractApplicationsTable = "contract_applications";
    private $profileTable = "user_profile";

    public $id;
    public $contractTitle;
    public $contractDetails;
    public $postedBy;
    public $contractPostDate;
    public $userEmail;
    public $attachmentUrl = "";
    public $attachmentExt;

    //Contract Application Form
    public $invoiceContact;
    public $invoiceEmail;
    public $invoiceTelephone;
    public $contactRef1;
    public $contactRef2;
    public $invoiceAddress;
    public $purchaseOrder;
    public $contactName;
    public $contactEmail;
    public $contactTelephone;
    public $appliedBy;
    public $appliedOn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function storeContractInDatabase()
    {
        $query = "INSERT INTO 
                        " . $this->contractTable . "
                    SET
                    contract_title = :contract_title, 
                    contract_details = :contract_details, 
                    posted_by = :posted_by,
                    contract_date = :contract_date,
                    is_document_uploaded = :is_document_uploaded,
                    attachment_extension = :attachment_extension";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->contractTitle = htmlspecialchars(strip_tags($this->contractTitle));
        $this->contractDetails = htmlspecialchars(strip_tags($this->contractDetails));
        $this->postedBy = htmlspecialchars(strip_tags($this->postedBy));
        $this->contractPostDate = htmlspecialchars(strip_tags($this->contractPostDate));

        // bind data
        $stmt->bindParam(":contract_title", $this->contractTitle);
        $stmt->bindParam(":contract_details", $this->contractDetails);
        $stmt->bindParam(":posted_by", $this->postedBy);
        $stmt->bindParam(":contract_date", $this->contractPostDate);
        $stmt->bindParam(":attachment_extension", $this->attachmentExt);

        if (strcmp($this->attachmentUrl, "") == 0) {
            $docAttached = false;
            $stmt->bindParam(":is_document_uploaded", $docAttached);
        } else {
            $docAttached = true;
            $stmt->bindParam(":is_document_uploaded", $docAttached);
        }

        if ($stmt->execute()) {
            $this->saveAttachment($this->conn->lastInsertId());
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Contract posted successfully!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Contract posting failed";
            return $response;
        }
    }

    public function storeApplication()
    {
        $query = "INSERT INTO 
                        " . $this->contractApplicationsTable . "
                    SET
                    applied_by = :applied_by, 
                    contract_id = :contract_id, 
                    applied_on = :applied_on,
                    invoice_contact = :invoice_contact,
                    invoice_email = :invoice_email,
                    invoice_telephone = :invoice_telephone,
                    invoice_contact_ref_1 = :invoice_contact_ref_1,
                    invoice_contact_ref_2 = :invoice_contact_ref_2,
                    invoice_address = :invoice_address,
                    purchase_order = :purchase_order,
                    com_contact_name = :com_contact_name,
                    com_contact_email = :com_contact_email,
                    com_contact_telephone = :com_contact_telephone,
                    attachment_ext = :attachment_ext";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->appliedBy = htmlspecialchars(strip_tags($this->appliedBy));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->appliedOn = htmlspecialchars(strip_tags($this->appliedOn));
        $this->invoiceContact = htmlspecialchars(strip_tags($this->invoiceContact));
        $this->invoiceEmail = htmlspecialchars(strip_tags($this->invoiceEmail));
        $this->invoiceTelephone = htmlspecialchars(strip_tags($this->invoiceTelephone));
        $this->contactRef1 = htmlspecialchars(strip_tags($this->contactRef1));
        $this->contactRef2 = htmlspecialchars(strip_tags($this->contactRef2));
        $this->invoiceAddress = htmlspecialchars(strip_tags($this->invoiceAddress));
        $this->purchaseOrder = htmlspecialchars(strip_tags($this->purchaseOrder));
        $this->contactName = htmlspecialchars(strip_tags($this->contactName));
        $this->contactEmail = htmlspecialchars(strip_tags($this->contactEmail));
        $this->contactTelephone = htmlspecialchars(strip_tags($this->contactTelephone));
        $this->attachmentExt = htmlspecialchars(strip_tags($this->attachmentExt));

        // bind data
        $stmt->bindParam(":applied_by", $this->appliedBy);
        $stmt->bindParam(":contract_id", $this->id);
        $stmt->bindParam(":applied_on", $this->appliedOn);
        $stmt->bindParam(":invoice_contact", $this->invoiceContact);
        $stmt->bindParam(":invoice_email", $this->invoiceEmail);
        $stmt->bindParam(":invoice_telephone", $this->invoiceTelephone);
        $stmt->bindParam(":invoice_contact_ref_1", $this->contactRef1);
        $stmt->bindParam(":invoice_contact_ref_2", $this->contactRef2);
        $stmt->bindParam(":invoice_address", $this->invoiceAddress);
        $stmt->bindParam(":purchase_order", $this->purchaseOrder);
        $stmt->bindParam(":com_contact_name", $this->contactName);
        $stmt->bindParam(":com_contact_email", $this->contactEmail);
        $stmt->bindParam(":com_contact_telephone", $this->contactTelephone);
        $stmt->bindParam(":attachment_ext", $this->attachmentExt);

        if ($stmt->execute()) {
            $this->saveSignedContract($this->conn->lastInsertId());
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Contract posted successfully!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Contract posting failed";
            return $response;
        }
    }

    public function saveAttachment($id)
    {
        $filePath = "../contracts/" . $this->userEmail . "/" . $id;
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
        $fp = fopen($filePath . "/" . $id . "." . $this->attachmentExt, "w+");

        // write the data in image file
        fwrite($fp, base64_decode($this->attachmentUrl));

        // close an open file pointer
        fclose($fp);
    }

    public function saveSignedContract($id)
    {
        $filePath = "../SignedContracts/" . $this->appliedBy . "/" . $id;
        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }
        $fp = fopen($filePath . "/" . $id . "." . $this->attachmentExt, "w+");

        // write the data in image file
        fwrite($fp, base64_decode($this->attachmentUrl));

        // close an open file pointer
        fclose($fp);
    }

    public function getContracts($email)
    {
        $sqlQuery = "SELECT contract.*,
                            user_profile.*
                             FROM " . $this->contractTable . " INNER JOIN "
            . $this->profileTable . " ON contract.posted_by = user_profile.email  WHERE posted_by = :posted_by";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":posted_by", $email);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $contract = new ContractModel();
            $contract->id = $row['id'];
            $contract->contractTitle = $row['contract_title'];
            $contract->contractDetails = $row['contract_details'];
            $contract->postedBy = $row['name'];
            $contract->contractDate = $row['contract_date'];
            $contract->city = $row['city'];
            $contract->userEmail = $row['posted_by'];
            $contract->isDocumentAttached = $row['is_document_uploaded'];
            $contract->attachmentExtension = $row['attachment_extension'];
            $contract->applicationsSubmitted = $this->getSubmittedApplicationsCount($row['id']);

            array_push($response, $contract);
        }
        $contracts = new ContractsList();
        $contracts->contracts = $response;
        return $contracts;
    }
    public function getAllContracts()
    {
        $sqlQuery = "SELECT contract.*,
                            user_profile.*
                             FROM " . $this->contractTable . " INNER JOIN "
            . $this->profileTable . " ON contract.posted_by = user_profile.email";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $contract = new ContractModel();
            $contract->id = $row['id'];
            $contract->contractTitle = $row['contract_title'];
            $contract->contractDetails = $row['contract_details'];
            $contract->postedBy = $row['name'];
            $contract->contractDate = $row['contract_date'];
            $contract->city = $row['city'];
            $contract->userEmail = $row['posted_by'];
            $contract->isDocumentAttached = $row['is_document_uploaded'];
            $contract->attachmentExtension = $row['attachment_extension'];
            $contract->applicationsSubmitted = $this->getSubmittedApplicationsCount($row['id']);

            array_push($response, $contract);
        }
        $contracts = new ContractsList();
        $contracts->contracts = $response;
        return $contracts;
    }

    private function getSubmittedApplicationsCount($id)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->contractApplicationsTable . "
                    WHERE 
                       contract_id = ?";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $id);

        $stmt->execute();

        $row = $stmt->rowCount();
        return $row;
    }

    public function getContractApplications($id)
    {   
        $getContractsQuery = "SELECT * FROM " . $this->contractApplicationsTable . " WHERE contract_id = ?";

        $stmt = $this->conn->prepare($getContractsQuery);

        $stmt->bindParam(1, $id);
        $stmt->execute();

        $contractApplicationsList = array();
        while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $contractApplication = new ContractApplicationModel();
            $contractApplication->id = $item['id'];
            $contractApplication->appliedBy = $item['applied_by'];
            $contractApplication->contractID = $item['contract_id'];
            $contractApplication->appliedOn = $item['applied_on'];
            $contractApplication->invoiceContact = $item['invoice_contact'];
            $contractApplication->invoiceEmail = $item['invoice_email'];
            $contractApplication->invoiceTelephone = $item['invoice_telephone'];
            $contractApplication->invoiceContactRef1 = $item['invoice_contact_ref_1'];
            $contractApplication->invoiceContactRef2 = $item['invoice_contact_ref_2'];
            $contractApplication->invoiceAddress = $item['invoice_address'];
            $contractApplication->purchaseOrder = $item['purchase_order'];
            $contractApplication->communicationContactName = $item['com_contact_name'];
            $contractApplication->communicationContactEmail = $item['com_contact_email'];
            $contractApplication->communicationContactTelephone = $item['com_contact_telephone'];
            $contractApplication->attachmentExtension = $item['attachment_ext'];

            array_push($contractApplicationsList, $contractApplication);
        }

        $contractApplications = new ContractApplications();
        $contractApplications->contractApplications = $contractApplicationsList;
        $response = new Response();
        $response->code = ResponseCodes::SUCCESS;
        $response->desc = "Contract Applications List";
        $response->data = $contractApplications;
        return $response;
    }
}
