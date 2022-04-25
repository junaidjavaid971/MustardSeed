<?php
include_once 'response.php';

class TherapySessions
{
    public $therapySession;

    // Db connection
    public function __construct()
    {
        $this->therapySession = new TherapySessionModel();
    }
}

class TherapySessionModel
{

    // Columns
    public $id;
    public $sessionType;
    public $sessionDuration;
    public $sessionBreaks;
    public $sessionPrice;
    public $mondayTimings;
    public $tuesdayTimings;
    public $wednesdayTimings;
    public $thursdayTimings;
    public $fridayTimings;
    public $saturdayTimings;
    public $sundayTimings;
    public $futureBookings;
    public $bookingMode;
    public $isAttandeesAllowed;
    public $isDiffPayeeAllowed;
    public $isSeriesOfSessions;
    public $sessionSeries;
    public $sessionVenues;
    public $isRecordingAllowed;
    public $offeredBy;
    public $userName;
    public $postedOn;

    // Db connection
    public function __construct()
    {
    }
}
