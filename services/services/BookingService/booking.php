<?php

use Stripe\Customer;

require '../../stripe/init.php';
include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';
include_once '../models/BookingModels/bookingModel.php';
include_once '../models/BookingModels/bookingList.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

class BookingService
{
    private $conn;
    private $apiKey = "";
    private $stripe;
    private $customer;
    private $payment_table = "payment";
    private $booking_table = "bookings";
    private $user_table = "user_profile";
    private $wallet_table = "wallet";
    private $feedback_table = "service_feedback";

    public $custName;
    public $custEmail;
    public $description;

    public $therapistEmail;
    public $date;
    public $startTime;
    public $endTime;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public static function withStripeToken($token, $db)
    {
        $instance = new self($db);
        $instance->conn = $db;
        $instance->apiKey = $token;
        $instance->stripe = new \Stripe\StripeClient($instance->apiKey);
        return $instance;
    }

    public function addCustomer()
    {
        $this->customer = $this->stripe->customers->create([
            'name' => $this->custName,
            'description' => $this->description,
            'email' => $this->custEmail,
            'payment_method' => 'pm_card_visa',
        ]);
        return $this->customer;
    }

    public function chargeAmount($description, $stripeToken)
    {
        $payDetails = $this->stripe->charges->create([
            'amount' => 50,
            'currency' => 'eur',
            'source' => $stripeToken,
            'description' => $description,
        ]);
        return $payDetails->jsonSerialize();
    }

    public function refundAmount($charge)
    {
        $payDetails = $this->stripe->refunds->create([
            'charge' => $charge,
        ]);
        return $payDetails->jsonSerialize();
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
                    charge = :charge";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $customerID = htmlspecialchars(strip_tags($this->customer->id));
        $this->custEmail = htmlspecialchars(strip_tags($this->custEmail));
        $amount = htmlspecialchars(strip_tags($amount));
        $date = date('Y-m-d H:i:s');

        // bind data
        $stmt->bindParam(":customer_id", $customerID);
        $stmt->bindParam(":customer_email", $this->custEmail);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":payment_date", $date);
        $stmt->bindParam(":charge", $charge);

        $stmt->execute();

        return $this->storeBookingInDB($this->conn->lastInsertId());
    }

    public function storeWalletPaymentDetailsInDB($amount, $charge)
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
        $paymentRationale = "Service Booking";

        // bind data
        $stmt->bindParam(":customer_id", $customerID);
        $stmt->bindParam(":customer_email", $this->custEmail);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":payment_date", $date);
        $stmt->bindParam(":charge", $charge);
        $stmt->bindParam(":payment_rationale", $paymentRationale);

        $stmt->execute();

        return $this->storeBookingInDB($this->conn->lastInsertId());
    }

    public function storeBookingInDB($paymentID)
    {
        $query = "INSERT INTO 
                " . $this->booking_table . "
                    SET
                    therapist_email = :therapist_email, 
                    booking_description = :booking_description, 
                    user_email = :user_email,
                    booking_date = :booking_date,
                    booking_status = :booking_status,
                    start_time = :start_time,
                    end_time = :end_time,
                    payment_id = :payment_id";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->therapistEmail = htmlspecialchars(strip_tags($this->therapistEmail));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->custEmail = htmlspecialchars(strip_tags($this->custEmail));
        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->startTime = htmlspecialchars(strip_tags($this->startTime));
        $this->endTime = htmlspecialchars(strip_tags($this->endTime));
        $paymentID = htmlspecialchars(strip_tags($paymentID));
        $bookingStatus = "Confirmed";

        // bind data
        $stmt->bindParam(":therapist_email", $this->therapistEmail);
        $stmt->bindParam(":booking_description", $this->description);
        $stmt->bindParam(":user_email", $this->custEmail);
        $stmt->bindParam(":booking_date", $this->date);
        $stmt->bindParam(":start_time", $this->startTime);
        $stmt->bindParam(":end_time", $this->endTime);
        $stmt->bindParam(":payment_id", $paymentID);
        $stmt->bindParam(":booking_status", $bookingStatus);

        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function getAllBookings($email)
    {
        $sqlQuery = "SELECT bookings.* , user_profile.* FROM " . $this->booking_table . " INNER JOIN "
            . $this->user_table . " ON bookings.therapist_email = user_profile.email WHERE bookings.user_email = :user_email";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":user_email", $email);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $booking = new BookingModel();
            $booking->id = $row['booking_id'];
            $booking->customerEmail = $row['user_email'];
            $booking->therapistEmail = $row['therapist_email'];
            $booking->therapistName = $row['name'];
            $booking->bookingDescription = $row['booking_description'];
            $booking->bookingDate = $row['booking_date'];
            $booking->bookingStatus = $row['booking_status'];
            $booking->startTime = $row['start_time'];
            $booking->endTime = $row['end_time'];
            $booking->paymentID = $row['payment_id'];

            array_push($response, $booking);
        }
        $bookings = new BookingsList();
        $bookings->bookings = $response;
        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Bookings List";
        $jsonResponse->data = $bookings;
        return $jsonResponse;
    }

    public function getAllPatients($email)
    {
        $sqlQuery = "SELECT * FROM " . $this->booking_table . " WHERE user_email = :user_email";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":user_email", $email);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $booking = new BookingModel();
            $booking->id = $row['booking_id'];
            $booking->customerEmail = $row['user_email'];
            $booking->therapistEmail = $row['therapist_email'];
            $booking->therapistName = $this->getName($row['therapist_email']);
            $booking->customerName = $this->getName($row['user_email']);
            $booking->bookingDescription = $row['booking_description'];
            $booking->bookingDate = $row['booking_date'];
            $booking->bookingStatus = $row['booking_status'];
            $booking->startTime = $row['start_time'];
            $booking->endTime = $row['end_time'];
            $booking->paymentID = $row['payment_id'];

            array_push($response, $booking);
        }
        $bookings = new BookingsList();
        $bookings->bookings = $response;

        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Bookings List";
        $jsonResponse->data = $bookings;
        return $jsonResponse;
    }

    public function getAllSessions($email)
    {
        $sqlQuery = "SELECT * FROM " . $this->booking_table . " WHERE user_email = :user_email";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":user_email", $email);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $booking = new BookingModel();
            $booking->id = $row['booking_id'];
            $booking->customerEmail = $row['user_email'];
            $booking->therapistEmail = $row['therapist_email'];
            $booking->therapistName = $this->getName($row['therapist_email']);
            $booking->customerName = $this->getName($row['user_email']);
            $booking->bookingDescription = $row['booking_description'];
            $booking->bookingDate = $row['booking_date'];
            $booking->bookingStatus = $row['booking_status'];
            $booking->startTime = $row['start_time'];
            $booking->endTime = $row['end_time'];
            $booking->paymentID = $row['payment_id'];

            array_push($response, $booking);
        }
        $bookings = new BookingsList();
        $bookings->bookings = $response;

        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Bookings List";
        $jsonResponse->data = $bookings;
        return $jsonResponse;
    }

    public function getActiveSessions($email)
    {
        $sqlQuery = "SELECT bookings.* , user_profile.* FROM " . $this->booking_table . " INNER JOIN "
            . $this->user_table . " ON bookings.user_email = user_profile.email 
                    WHERE bookings.user_email = :user_email AND booking_status = :booking_status";

        $stmt = $this->conn->prepare($sqlQuery);
        $bookingStatus = "Confirmed";
        $stmt->bindParam(":user_email", $email);
        $stmt->bindParam(":booking_status", $bookingStatus);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $booking = new BookingModel();
            $booking->id = $row['booking_id'];
            $booking->customerEmail = $row['user_email'];
            $booking->customerName = $row['name'];
            $booking->therapistEmail = $row['therapist_email'];
            $booking->therapistName = $row['name'];
            $booking->therapistPhoneNumber = $row['contact_number'];
            $booking->therapistAddress = $row['address'];
            $booking->bookingDescription = $row['booking_description'];
            $booking->bookingDate = $row['booking_date'];
            $booking->bookingStatus = $row['booking_status'];
            $booking->startTime = $row['start_time'];
            $booking->endTime = $row['end_time'];
            $booking->paymentID = $row['payment_id'];

            array_push($response, $booking);
        }
        $bookings = new BookingsList();
        $bookings->bookings = $response;

        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Active Sessions";
        $jsonResponse->data = $bookings;
        return $jsonResponse;
    }
    public function getActiveSessionsGroup($email)
    {
        $sqlQuery = "SELECT bookings.* , user_profile.* FROM " . $this->booking_table . " INNER JOIN "
            . $this->user_table . " ON bookings.user_email = user_profile.email 
                    WHERE bookings.user_email = :user_email AND booking_status = :booking_status GROUP BY bookings.user_email";

        $stmt = $this->conn->prepare($sqlQuery);
        $bookingStatus = "Confirmed";
        $stmt->bindParam(":user_email", $email);
        $stmt->bindParam(":booking_status", $bookingStatus);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $booking = new BookingModel();
            $booking->id = $row['booking_id'];
            $booking->customerEmail = $row['user_email'];
            $booking->customerName = $row['name'];
            $booking->therapistEmail = $row['therapist_email'];
            $booking->therapistName = $row['name'];
            $booking->therapistPhoneNumber = $row['contact_number'];
            $booking->therapistAddress = $row['address'];
            $booking->bookingDescription = $row['booking_description'];
            $booking->bookingDate = $row['booking_date'];
            $booking->bookingStatus = $row['booking_status'];
            $booking->startTime = $row['start_time'];
            $booking->endTime = $row['end_time'];
            $booking->paymentID = $row['payment_id'];

            array_push($response, $booking);
        }
        $bookings = new BookingsList();
        $bookings->bookings = $response;

        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Active Sessions";
        $jsonResponse->data = $bookings;
        return $jsonResponse;
    }

    public function getRecentVisits($email)
    {
        $sqlQuery = "SELECT * FROM " . $this->booking_table . " WHERE user_email = :user_email ORDER BY booking_id DESC LIMIT 5";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":user_email", $email);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $booking = new BookingModel();
            $booking->id = $row['booking_id'];
            $booking->customerEmail = $row['user_email'];
            $booking->therapistEmail = $row['therapist_email'];
            $booking->therapistName = $this->getName($row['therapist_email']);
            $booking->customerName = $this->getName($row['user_email']);
            $booking->bookingDescription = $row['booking_description'];
            $booking->bookingDate = $row['booking_date'];
            $booking->bookingStatus = $row['booking_status'];
            $booking->startTime = $row['start_time'];
            $booking->endTime = $row['end_time'];
            $booking->paymentID = $row['payment_id'];

            array_push($response, $booking);
        }
        $bookings = new BookingsList();
        $bookings->bookings = $response;

        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Bookings List";
        $jsonResponse->data = $bookings;
        return $jsonResponse;
    }

    public function getBookingDetail($bookingID)
    {
        $sqlQuery = "SELECT bookings.* , user_profile.* FROM " . $this->booking_table . " INNER JOIN "
            . $this->user_table . " ON bookings.therapist_email = user_profile.email WHERE bookings.booking_id = :booking_id";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":booking_id", $bookingID);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $booking = new BookingModel();
            $booking->id = $row['booking_id'];
            $booking->customerEmail = $row['user_email'];
            $booking->therapistEmail = $row['therapist_email'];
            $booking->therapistName = $row['name'];
            $booking->therapistPhoneNumber = $row['contact_number'];
            $booking->therapistAddress = $row['address'];
            $booking->bookingDescription = $row['booking_description'];
            $booking->bookingDate = $row['booking_date'];
            $booking->bookingStatus = $row['booking_status'];
            $booking->startTime = $row['start_time'];
            $booking->endTime = $row['end_time'];
            $booking->paymentID = $row['payment_id'];

            array_push($response, $booking);
        }
        $bookings = new BookingsList();
        $bookings->bookings = $response;
        $jsonResponse = new Response();
        $jsonResponse->code = ResponseCodes::SUCCESS;
        $jsonResponse->desc = "Bookings List";
        $jsonResponse->data = $bookings;
        return $jsonResponse;
    }

    public function getPaymentCharge($bookingID)
    {
        $sqlQuery = "SELECT bookings.* , payment.* FROM " . $this->booking_table . " INNER JOIN "
            . $this->payment_table . " ON bookings.payment_id = payment.p_id WHERE bookings.booking_id = :booking_id";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":booking_id", $bookingID);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['charge'];
    }

    public function updateBookingStatus($bookingID, $bookingStatus)
    {
        $sqlQuery = "UPDATE
                " . $this->booking_table . "
                SET 
                    booking_status = :booking_status
                WHERE 
                    booking_id = :booking_id";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":booking_id", $bookingID);
        $stmt->bindParam(":booking_status", $bookingStatus);

        if ($stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Session " . $bookingStatus;
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Error Occured!";
            return $response;
        }
    }

    public function changeTherapist($bookingID, $therapistEmail)
    {
        $sqlQuery = "UPDATE
                " . $this->booking_table . "
                SET 
                    therapist_email = :therapist_email
                WHERE 
                    booking_id = :booking_id";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":booking_id", $bookingID);
        $stmt->bindParam(":therapist_email", $therapistEmail);

        if ($stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Therapist Changed Successfully";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Error Occured!";
            return $response;
        }
    }

    public function addWallet($amount, $email)
    {
        $query = "INSERT INTO 
                " . $this->wallet_table . "
                    SET
                    amount = :amount,
                    user_email = :user_email";

        $stmt = $this->conn->prepare($query);

        // bind data
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":user_email", $email);

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

    public function checkWallet($amount, $email)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->wallet_table . "
                    WHERE 
                       user_email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $email);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $amount = $row['amount'] + $amount;
            return $this->updateWallet($amount, $email);
        } else {
            return $this->addWallet($amount, $email);
        }
    }

    public function refundWallet($amount, $email, $bookingID)
    {
        $sqlQuery = "UPDATE
                " . $this->wallet_table . "
                SET 
                    amount = :amount
                WHERE 
                    user_email = :user_email";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":user_email", $email);
        $stmt->execute();

        return $this->updateBookingStatus($bookingID, "Cancelled");
    }

    public function updateWallet($amount, $email)
    {
        $sqlQuery = "UPDATE
                " . $this->wallet_table . "
                SET 
                    amount = :amount
                WHERE 
                    user_email = :user_email";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":user_email", $email);

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

    public function getWalletAmount($email)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->wallet_table . "
                    WHERE 
                       user_email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $email);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (strcmp($row['amount'], "") == 0) {
            return "0";
        } else {
            return $row['amount'];
        }
    }

    public function getFeedback($bookingID)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->feedback_table . "
                    WHERE 
                       booking_id = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $bookingID);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row == null) {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "No feedback";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Feedback Found";
            return $response;
        }
    }

    public function rateReview($bookingID, $rating, $review)
    {
        $sqlQuery = "INSERT INTO
                        " . $this->feedback_table . "
                    SET 
                       rating = :rating,
                       review = :review,
                       booking_id =:booking_id";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(":rating", $rating);
        $stmt->bindParam(":review", $review);
        $stmt->bindParam(":booking_id", $bookingID);

        if ($stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Thank you for your feedback";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "There was an error in processing your feedback. Please try again!";
            return $response;
        }
    }

    public function getName($email)
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->user_table . "
                    WHERE 
                       email = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $email);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['name'];
    }
}
