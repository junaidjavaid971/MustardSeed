<?php
//Include Configuration File
include('../config/config-google-sign-in.php');
include_once '../config/database.php';
include_once '../services/UserManagementService/user.php';
include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;

    $user->name = $name;
    $user->email = $email;
    $user->password = "";
    $user->created = date('Y-m-d H:i:s');
    $user->verified = 1;

    $response = $user->saveGoogleUser(true);
    if (strcmp($response->code, ResponseCodes::SUCCESS) == 0) {
        header("Location: http://localhost/MustardSeed/login1.html?email=" . $email);
    } else {
        header("Location: http://localhost/MustardSeed/register.html");
    }
} else {
    header("Location: " . $client->createAuthUrl());
}
