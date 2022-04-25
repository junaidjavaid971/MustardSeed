<?php

include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';
include_once '../enums/constants.php';
include_once '../models/ServiceModels/serviceModels.php';
include_once '../models/ServiceModels/serviceList.php';
include_once '../models/ResponseModels/generic.php';


class Services
{

    private $conn;
    private $db_table = "services";

    public $id;
    public $serviceTitle;
    public $serviceDesc;
    public $serviceCost;
    public $serviceDuration;
    public $serviceType;
    public $email;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function addService()
    {
        $query = "INSERT INTO 
                        " . $this->db_table . "
                    SET
                    service_title = :service_title, 
                    service_cost = :service_cost, 
                    service_type = :service_type,
                    service_duration = :service_duration,
                    user_email = :user_email,
                    service_desc = :service_desc";

        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->serviceTitle = htmlspecialchars(strip_tags($this->serviceTitle));
        $this->serviceDesc = htmlspecialchars(strip_tags($this->serviceDesc));
        $this->serviceType = htmlspecialchars(strip_tags($this->serviceType));
        $this->serviceDuration = htmlspecialchars(strip_tags($this->serviceDuration));
        $this->serviceCost = htmlspecialchars(strip_tags($this->serviceCost));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind data
        $stmt->bindParam(":service_title", $this->serviceTitle);
        $stmt->bindParam(":service_desc", $this->serviceDesc);
        $stmt->bindParam(":service_type", $this->serviceType);
        $stmt->bindParam(":service_duration", $this->serviceDuration);
        $stmt->bindParam(":service_cost", $this->serviceCost);
        $stmt->bindParam(":user_email", $this->email);

        if ($stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Service saved successfully!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "An error occured while saving the service. Please try again!";
            return $response;
        }
    }

    public function getServices()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table;

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $service = new ServicesModal();
            $service->id = $row['id'];
            $service->serviceTitle = $row['service_title'];
            $service->serviceDesc = $row['service_desc'];
            $service->serviceType = $row['service_type'];
            $service->serviceDuration = $row['service_duration'];
            $service->serviceCost = $row['service_cost'];
            $service->email = $row['user_email'];

            array_push($response, $service);
        }
        $services = new ServicesList();
        $services->services = $response;
        return $services;
    }
    public function getServiceOnID()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table . "  WHERE  id = :id";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $response = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $service = new ServicesModal();
            $service->id = $row['id'];
            $service->serviceTitle = $row['service_title'];
            $service->serviceDesc = $row['service_desc'];
            $service->serviceType = $row['service_type'];
            $service->serviceDuration = $row['service_duration'];
            $service->serviceCost = $row['service_cost'];
            $service->email = $row['user_email'];

            array_push($response, $service);
        }
        $services = new ServicesList();
        $services->services = $response;
        return $services;
    }
}
