<?php

use Stripe\Customer;

require '../../stripe/init.php';
include_once '../models/response.php';
include_once '../enums/responsecodes.php';
include_once '../models/TherapySessionModels/therapySessionModel.php';
include_once '../models/TherapySessionModels/therapySessionList.php';

class TherapySessionService
{
    private $conn;
    private $therapySessionTable = "therapy_sessions";
    private $profileTable = "user_profile";
    private $paymentTable = "payment";
    private $bookedSessionTable = "booked_sessions";

    private $stripe;

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
    public function storeTherapySessionInDatabase($therapySession)
    {
        $query = "INSERT INTO 
                        " . $this->therapySessionTable . "
                    SET
                    session_type = :session_type, 
                    session_duration = :session_duration, 
                    session_break = :session_break,
                    session_price = :session_price,
                    monday_timings = :monday_timings,
                    tuesday_timings = :tuesday_timings,
                    wednesday_timings = :wednesday_timings,
                    thursday_timings = :thursday_timings,
                    friday_timings = :friday_timings,
                    saturday_timings = :saturday_timings,
                    sunday_timings = :sunday_timings,
                    future_bookings = :future_bookings,
                    booking_mode = :booking_mode,
                    attandees_allowed = :attandees_allowed,
                    diff_payee_allowed = :diff_payee_allowed,
                    is_series_of_sessions = :is_series_of_sessions,
                    session_series = :session_series,
                    session_venues = :session_venues,
                    is_recording_allowed = :is_recording_allowed,
                    offered_by = :offered_by,
                    posted_on = :posted_on";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $therapySession->sessionType = htmlspecialchars(strip_tags($therapySession->sessionType));
        $therapySession->sessionDuration = htmlspecialchars(strip_tags($therapySession->sessionDuration));
        $therapySession->sessionBreaks = htmlspecialchars(strip_tags($therapySession->sessionBreaks));
        $therapySession->sessionPrice = htmlspecialchars(strip_tags($therapySession->sessionPrice));
        $therapySession->mondayTimings = htmlspecialchars(strip_tags($therapySession->mondayTimings));
        $therapySession->tuesdayTimings = htmlspecialchars(strip_tags($therapySession->tuesdayTimings));
        $therapySession->wednesdayTimings = htmlspecialchars(strip_tags($therapySession->wednesdayTimings));
        $therapySession->thursdayTimings = htmlspecialchars(strip_tags($therapySession->thursdayTimings));
        $therapySession->fridayTimings = htmlspecialchars(strip_tags($therapySession->fridayTimings));
        $therapySession->saturdayTimings = htmlspecialchars(strip_tags($therapySession->saturdayTimings));
        $therapySession->sundayTimings = htmlspecialchars(strip_tags($therapySession->sundayTimings));
        $therapySession->futureBookings = htmlspecialchars(strip_tags($therapySession->futureBookings));
        $therapySession->bookingMode = htmlspecialchars(strip_tags($therapySession->bookingMode));
        $therapySession->isAttandeesAllowed = htmlspecialchars(strip_tags($therapySession->isAttandeesAllowed));
        $therapySession->isDiffPayeeAllowed = htmlspecialchars(strip_tags($therapySession->isDiffPayeeAllowed));
        $therapySession->isSeriesOfSessions = htmlspecialchars(strip_tags($therapySession->isSeriesOfSessions));
        $therapySession->sessionSeries = htmlspecialchars(strip_tags($therapySession->sessionSeries));
        $therapySession->sessionVenues = htmlspecialchars(strip_tags($therapySession->sessionVenues));
        $therapySession->isRecordingAllowed = htmlspecialchars(strip_tags($therapySession->isRecordingAllowed));
        $therapySession->offeredBy = htmlspecialchars(strip_tags($therapySession->offeredBy));

        // bind data
        $stmt->bindParam(":session_type", $therapySession->sessionType);
        $stmt->bindParam(":session_duration", $therapySession->sessionDuration);
        $stmt->bindParam(":session_break", $therapySession->sessionBreaks);
        $stmt->bindParam(":session_price", $therapySession->sessionPrice);

        $stmt->bindParam(":monday_timings", $therapySession->mondayTimings);
        $stmt->bindParam(":tuesday_timings", $therapySession->tuesdayTimings);
        $stmt->bindParam(":wednesday_timings", $therapySession->wednesdayTimings);
        $stmt->bindParam(":thursday_timings", $therapySession->thursdayTimings);
        $stmt->bindParam(":friday_timings", $therapySession->fridayTimings);
        $stmt->bindParam(":saturday_timings", $therapySession->saturdayTimings);
        $stmt->bindParam(":sunday_timings", $therapySession->sundayTimings);

        $stmt->bindParam(":future_bookings", $therapySession->futureBookings);
        $stmt->bindParam(":booking_mode", $therapySession->bookingMode);
        $stmt->bindParam(":attandees_allowed", $therapySession->isAttandeesAllowed);
        $stmt->bindParam(":diff_payee_allowed", $therapySession->isDiffPayeeAllowed);
        $stmt->bindParam(":is_series_of_sessions", $therapySession->isSeriesOfSessions);
        $stmt->bindParam(":session_series", $therapySession->sessionSeries);

        $stmt->bindParam(":session_venues", $therapySession->sessionVenues);
        $stmt->bindParam(":is_recording_allowed", $therapySession->isRecordingAllowed);
        $stmt->bindParam(":offered_by", $therapySession->offeredBy);
        $stmt->bindParam(":posted_on", date('Y-m-d H:i:s'));

        if ($stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Session Details Saved Successfully!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "An unexpected error occured while saving the session details!";
            return $response;
        }
    }

    public function getTherapySessionsForSingleUser($email)
    {
        $sqlQuery = "SELECT therapy_sessions.*,
                            user_profile.name, user_profile.email
                             FROM " . $this->therapySessionTable . " INNER JOIN "
            . $this->profileTable . " ON therapy_sessions.offered_by = user_profile.email  WHERE offered_by = :offered_by";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":offered_by", $email);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $therapySessionModel = new TherapySessionModel();
            $therapySessionModel->id = $row['id'];
            $therapySessionModel->sessionType = $row['session_type'];
            $therapySessionModel->sessionDuration = $row['session_duration'];
            $therapySessionModel->sessionBreaks = $row['session_break'];
            $therapySessionModel->sessionPrice = $row['session_price'];
            $therapySessionModel->mondayTimings = $row['monday_timings'];
            $therapySessionModel->tuesdayTimings = $row['tuesday_timings'];
            $therapySessionModel->wednesdayTimings = $row['wednesday_timings'];
            $therapySessionModel->thursdayTimings = $row['thursday_timings'];
            $therapySessionModel->fridayTimings = $row['friday_timings'];
            $therapySessionModel->saturdayTimings = $row['saturday_timings'];
            $therapySessionModel->sundayTimings = $row['sunday_timings'];
            $therapySessionModel->futureBookings = $row['future_bookings'];
            $therapySessionModel->bookingMode = $row['booking_mode'];
            $therapySessionModel->isAttandeesAllowed = $row['attandees_allowed'];
            $therapySessionModel->isDiffPayeeAllowed = $row['diff_payee_allowed'];
            $therapySessionModel->isSeriesOfSessions = $row['is_series_of_sessions'];
            $therapySessionModel->sessionSeries = $row['session_series'];
            $therapySessionModel->sessionVenues = $row['session_venues'];
            $therapySessionModel->isRecordingAllowed = $row['is_recording_allowed'];
            $therapySessionModel->offeredBy = $row['offered_by'];
            $therapySessionModel->userName = $row['name'];
            $therapySessionModel->postedOn = $row['posted_on'];

            array_push($response, $therapySessionModel);
        }
        $therapySessions = new TherapySessionsList();
        $therapySessions->therapySessions = $response;
        return $therapySessions;
    }


    public function getSpecificTherapySessionByID($bookingID)
    {
        $sqlQuery = "SELECT therapy_sessions.*,
                            user_profile.name, user_profile.email
                             FROM " . $this->therapySessionTable . " INNER JOIN "
            . $this->profileTable . " ON therapy_sessions.offered_by = user_profile.email  WHERE therapy_sessions.id = :id LIMIT 1";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":id", $bookingID);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $therapySessionModel = new TherapySessionModel();
        $therapySessionModel->id = $row['id'];
        $therapySessionModel->sessionType = $row['session_type'];
        $therapySessionModel->sessionDuration = $row['session_duration'];
        $therapySessionModel->sessionBreaks = $row['session_break'];
        $therapySessionModel->sessionPrice = $row['session_price'];
        $therapySessionModel->mondayTimings = $row['monday_timings'];
        $therapySessionModel->tuesdayTimings = $row['tuesday_timings'];
        $therapySessionModel->wednesdayTimings = $row['wednesday_timings'];
        $therapySessionModel->thursdayTimings = $row['thursday_timings'];
        $therapySessionModel->fridayTimings = $row['friday_timings'];
        $therapySessionModel->saturdayTimings = $row['saturday_timings'];
        $therapySessionModel->sundayTimings = $row['sunday_timings'];
        $therapySessionModel->futureBookings = $row['future_bookings'];
        $therapySessionModel->bookingMode = $row['booking_mode'];
        $therapySessionModel->isAttandeesAllowed = $row['attandees_allowed'];
        $therapySessionModel->isDiffPayeeAllowed = $row['diff_payee_allowed'];
        $therapySessionModel->isSeriesOfSessions = $row['is_series_of_sessions'];
        $therapySessionModel->sessionSeries = $row['session_series'];
        $therapySessionModel->sessionVenues = $row['session_venues'];
        $therapySessionModel->isRecordingAllowed = $row['is_recording_allowed'];
        $therapySessionModel->offeredBy = $row['offered_by'];
        $therapySessionModel->userName = $row['name'];
        $therapySessionModel->postedOn = $row['posted_on'];

        return $therapySessionModel;
    }

    public function getTherapySessions()
    {
        $sqlQuery = "SELECT therapy_sessions.*,
                    user_profile.name, user_profile.email
                    FROM " . $this->therapySessionTable . " INNER JOIN "
            . $this->profileTable . " ON therapy_sessions.offered_by = user_profile.email";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $therapySessionModel = new TherapySessionModel();
            $therapySessionModel->id = $row['id'];
            $therapySessionModel->sessionType = $row['session_type'];
            $therapySessionModel->sessionDuration = $row['session_duration'];
            $therapySessionModel->sessionBreaks = $row['session_break'];
            $therapySessionModel->sessionPrice = $row['session_price'];
            $therapySessionModel->mondayTimings = $row['monday_timings'];
            $therapySessionModel->tuesdayTimings = $row['tuesday_timings'];
            $therapySessionModel->wednesdayTimings = $row['wednesday_timings'];
            $therapySessionModel->thursdayTimings = $row['thursday_timings'];
            $therapySessionModel->fridayTimings = $row['friday_timings'];
            $therapySessionModel->saturdayTimings = $row['saturday_timings'];
            $therapySessionModel->sundayTimings = $row['sunday_timings'];
            $therapySessionModel->futureBookings = $row['future_bookings'];
            $therapySessionModel->bookingMode = $row['booking_mode'];
            $therapySessionModel->isAttandeesAllowed = $row['attandees_allowed'];
            $therapySessionModel->isDiffPayeeAllowed = $row['diff_payee_allowed'];
            $therapySessionModel->isSeriesOfSessions = $row['is_series_of_sessions'];
            $therapySessionModel->sessionSeries = $row['session_series'];
            $therapySessionModel->sessionVenues = $row['session_venues'];
            $therapySessionModel->isRecordingAllowed = $row['is_recording_allowed'];
            $therapySessionModel->offeredBy = $row['offered_by'];
            $therapySessionModel->userName = $row['name'];
            $therapySessionModel->userName = $row['name'];
            $therapySessionModel->postedOn = $row['posted_on'];

            array_push($response, $therapySessionModel);
        }
        $therapySessions = new TherapySessionsList();
        $therapySessions->therapySessions = $response;
        return $therapySessions;
    }

    function addCustomer($name, $email)
    {
        $customer = $this->stripe->customers->create([
            'name' => $name,
            'description' => "Therapy Session Booking",
            'email' => $email,
            'payment_method' => 'pm_card_visa',
        ]);
        return $customer;
    }


    public function chargeAmount($stripeToken, $price)
    {
        $payDetails = $this->stripe->charges->create([
            'amount' => $price,
            'currency' => 'eur',
            'source' => $stripeToken,
            'description' => "Therapy Session Booking",
        ]);
        return $payDetails->jsonSerialize();
    }

    function bookTherapySession($paymentID, $sessionID, $email)
    {
        $query = "INSERT INTO 
                        " . $this->bookedSessionTable . "
                    SET
                    booked_by = :booked_by, 
                    payment_id = :payment_id,
                    session_id = :session_id,
                    booked_on = :booked_on";

        $stmt = $this->conn->prepare($query);

        $date = date('Y-m-d H:i:s');

        // bind data
        $stmt->bindParam(":booked_by", $email);
        $stmt->bindParam(":payment_id", $paymentID);
        $stmt->bindParam(":session_id", $sessionID);
        $stmt->bindParam(":booked_on", $date);

        if ($stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Thank you for booking the therapy session with us!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "An unexpected error occured while booking the session!";
            return $response;
        }
    }

    public function storePaymentDetailsInDB($payment)
    {
        $query = "INSERT INTO 
                        " . $this->paymentTable . "
                    SET
                    customer_id = :customer_id, 
                    customer_email = :customer_email, 
                    amount = :amount,
                    payment_date = :payment_date,
                    charge = :charge,
                    payment_rationale = :payment_rationale";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $charge = htmlspecialchars(strip_tags($payment['0']));
        $amount = htmlspecialchars(strip_tags($payment['1']));
        $customerID = htmlspecialchars(strip_tags($payment['2']));
        $email = htmlspecialchars(strip_tags($payment['3']));
        $date = date('Y-m-d H:i:s');
        $paymentRationale = "Therapy Session Booking";

        // bind data
        $stmt->bindParam(":customer_id", $customerID);
        $stmt->bindParam(":customer_email", $email);
        $stmt->bindParam(":amount", $amount);
        $stmt->bindParam(":payment_date", $date);
        $stmt->bindParam(":charge", $charge);
        $stmt->bindParam(":payment_rationale", $paymentRationale);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    
    public function getBookedTherapySessionsForSingleUser($email)
    {
        $sqlQuery = "SELECT booked_sessions.*,
                            therapy_sessions.*,
                            user_profile.name, user_profile.email
                             FROM " . $this->bookedSessionTable . " INNER JOIN "
            . $this->therapySessionTable . " ON booked_sessions.session_id = therapy_sessions.id INNER JOIN " . 
            $this->profileTable . " ON booked_sessions.booked_by = user_profile.email WHERE email = :email";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $therapySessionModel = new TherapySessionModel();
            $therapySessionModel->id = $row['id'];
            $therapySessionModel->sessionType = $row['session_type'];
            $therapySessionModel->sessionDuration = $row['session_duration'];
            $therapySessionModel->sessionBreaks = $row['session_break'];
            $therapySessionModel->sessionPrice = $row['session_price'];
            $therapySessionModel->mondayTimings = $row['monday_timings'];
            $therapySessionModel->tuesdayTimings = $row['tuesday_timings'];
            $therapySessionModel->wednesdayTimings = $row['wednesday_timings'];
            $therapySessionModel->thursdayTimings = $row['thursday_timings'];
            $therapySessionModel->fridayTimings = $row['friday_timings'];
            $therapySessionModel->saturdayTimings = $row['saturday_timings'];
            $therapySessionModel->sundayTimings = $row['sunday_timings'];
            $therapySessionModel->futureBookings = $row['future_bookings'];
            $therapySessionModel->bookingMode = $row['booking_mode'];
            $therapySessionModel->isAttandeesAllowed = $row['attandees_allowed'];
            $therapySessionModel->isDiffPayeeAllowed = $row['diff_payee_allowed'];
            $therapySessionModel->isSeriesOfSessions = $row['is_series_of_sessions'];
            $therapySessionModel->sessionSeries = $row['session_series'];
            $therapySessionModel->sessionVenues = $row['session_venues'];
            $therapySessionModel->isRecordingAllowed = $row['is_recording_allowed'];
            $therapySessionModel->offeredBy = $row['offered_by'];
            $therapySessionModel->userName = $row['name'];
            $therapySessionModel->postedOn = $row['posted_on'];

            array_push($response, $therapySessionModel);
        }
        $therapySessions = new TherapySessionsList();
        $therapySessions->therapySessions = $response;
        return $therapySessions;
    }
    function deleteSession($sessionID)
    {
        $sqlQuery = "DELETE FROM " . $this->therapySessionTable . " WHERE id = :id";
        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(":id", $sessionID);

        if ($stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Session deleted successfully!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Error occured while deleting the session!";
            return $response;
        }
    }
}
