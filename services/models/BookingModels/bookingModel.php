<?php
include_once 'response.php';

class Bookings
{
    public $booking;

    // Db connection
    public function __construct()
    {
        $this->booking = new BookingModel();
    }
}

class BookingModel
{

    // Columns
    public $id;
    public $customerEmail;
    public $therapistEmail;
    public $therapistName;
    public $customerName;
    public $therapistPhoneNumber;
    public $therapistAddress;
    public $bookingDescription;
    public $bookingStatus;
    public $bookingDate;
    public $startTime;
    public $endTime;
    public $paymentID;

    // Db connection
    public function __construct()
    {
    }
}
