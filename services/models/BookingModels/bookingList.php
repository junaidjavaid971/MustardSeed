<?php
    include_once '../models/response.php';
    include_once 'bookingModel.php';

    class BookingsList{
        public $bookings;

        // Db connection
        public function __construct(){
            $this->bookings = new Bookings();
        }
    }
