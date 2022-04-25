<?php
require_once '../../vendor/autoload.php';
  
// init configuration
$clientID = '974243198962-q2t0l45nq949kvt5ptqe06n78qd3f3av.apps.googleusercontent.com';
$clientSecret = 'gQgFtQxTgk1OM4mEIXEAb2oG';
$redirectUri = 'http://localhost/MustardSeed/services/api/google-sign-in.php';
   
// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
