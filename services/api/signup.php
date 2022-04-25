<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../config/database.php';
    include_once '../services/UserManagementService/user.php';
    include_once '../models/ResponseModels/response.php';
    include_once '../enums/responsecodes.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new User($db);

    $data = json_decode(file_get_contents("php://input"));
	
    $item->name = $data->Data->name;
    $item->email = $data->Data->email;
    $item->password = $data->Data->password;
    $item->contactNumber = $data->Data->contactNumber;
    $item->profilePicture = $data->Data->profilePicture;
    $item->created = date('Y-m-d H:i:s');
    
    $response = $item->createUser();
    if(strcmp($response->code,ResponseCodes::SUCCESS)==0){
        $response = new Response();
        $response->code = ResponseCodes::SUCCESS;
        $response->desc = "Account Created Successfully";
        echo json_encode($response);
    } else{
        echo json_encode($response);
    }
?>