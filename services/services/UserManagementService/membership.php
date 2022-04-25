<?php
include_once 'services/models/ResponseModels/response.php';
include_once '../models/MembershipModels/membershipList.php';
include_once '../models/MembershipModels/membershipModel.php';
require 'phpmailer/PHPMailerAutoload.php';

class Membership
{

    // Connection
    private $conn;

    // Table
    private $db_table = "membership_plans";
    private $profile_table = "user_profile";
    private $payment_table = "payment";
    private $wallet_table = "wallet";

    // Columns
    public $id;
    public $currentPlan;
    public $userEmail;
    public $purchasedOn;
    public $expiresOn;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // UPDATE
    public function getCurrentMembershipPlan($email)
    {
        $sqlQuery = "SELECT
                        membership_plans.*
                      FROM
                        " . $this->profile_table  . " INNER JOIN " . $this->db_table . "
                        ON user_profile.membership = membership_plans.plan AND user_profile.email = membership_plans.user_email
                    WHERE 
                       user_profile.email = :email
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $membership = new MembershipModel();
        $membership->id = $row['id'];
        $membership->userEmail = $row['user_email'];
        $membership->plan = $row['plan'];
        $membership->purchasedOn = $row['purchased_on'];
        $membership->expiresOn = $row['expires_on'];

        $response = new Response();
        $response->code = ResponseCodes::SUCCESS;
        $response->desc = "Membership Plans";
        $response->data = $membership;

        return $response;
    }

    // UPDATE
    public function purchaseMembership($paymentID)
    {
        $membershipQuery = "INSERT INTO 
                        " . $this->db_table . "
                    SET
                    user_email = :user_email,
                    plan = :plan,
                    purchased_on = :purchased_on,
                    expires_on = :expires_on,
                    payment_id = :payment_id";

        $stmt = $this->conn->prepare($membershipQuery);
        // bind data
        $stmt->bindParam(":user_email", $this->userEmail);
        $stmt->bindParam(":plan", $this->currentPlan);
        $stmt->bindParam(":purchased_on", $this->purchasedOn);
        $stmt->bindParam(":expires_on", $this->expiresOn);
        $stmt->bindParam(":payment_id", $paymentID);

        if (!$stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Unexpected error occured while upgrading the membership plan. Please try again!";
            return $response;
        } else {
            return $this->upgradeBookingStatus();
        }
    }

    private function upgradeBookingStatus()
    {
        $sqlQuery = "UPDATE
        " . $this->profile_table . "
        SET 
            membership = :membership
        WHERE 
            email = :email";

        $stmt = $this->conn->prepare($sqlQuery);

        // bind data
        $stmt->bindParam(":email", $this->userEmail);
        $stmt->bindParam(":membership", $this->currentPlan);

        if ($stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Membership Plan Upgraded!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Unexpected error occured while saving the membership plan. Please try again!";
            return $response;
        }
    }

    public function storePaymentDetailsInDB($amount, $charge)
    {
        $query = "INSERT INTO 
                        " . $this->payment_table . "
                    SET
                    customer_id = :customer_id, 
                    customer_email = :customer_email, 
                    amount = :amount,
                    payment_date = :payment_date,
                    payment_rationale = :payment_rationale,
                    charge = :charge";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $customerID = "";
        $this->custEmail = htmlspecialchars(strip_tags($this->custEmail));
        $amount = htmlspecialchars(strip_tags($amount));
        $date = date('Y-m-d H:i:s');
        $paymentRationale = "Membership Upgrade" . " - " . $this->currentPlan;

        // bind data
        $stmt->bindParam(":customer_id", $customerID);
        $stmt->bindParam(":customer_email", $this->custEmail);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":payment_date", $date);
        $stmt->bindParam(":charge", $charge);
        $stmt->bindParam(":payment_rationale", $paymentRationale);

        $stmt->execute();

        return $this->purchaseMembership($this->conn->lastInsertId());
    }

    public function getWalletAmount()
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->wallet_table . "
                    WHERE 
                       user_email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->userEmail);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (strcmp($row['amount'], "") == 0) {
            return "0";
        } else {
            return $row['amount'];
        }
    }

    public function updateWallet($amount)
    {
        $sqlQuery = "UPDATE
                " . $this->wallet_table . "
                SET 
                    amount = :amount
                WHERE 
                    user_email = :user_email";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":user_email", $this->userEmail);

        if ($stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Wallet Updated";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Error Occured!";
            return $response;
        }
    }
}
