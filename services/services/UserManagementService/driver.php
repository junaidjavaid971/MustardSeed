<?php

    include_once '../models/ResponseModels/response.php';
    include_once '../enums/responsecodes.php';
    class Driver{

        // Connection
        private $conn;

        // Table
        private $db_table = "User";
        private $userType = "Driver";
        private $driver_table = "drivers";
        
        // Columns
        public $id;
        public $name;
        public $email;
        public $password;
        public $contactNumber;
        public $created;
        public $dateOfBirth;
        public $nic;
        public $profilePicUri;

        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }

        // GET ALL
        public function getDrivers(){
            $sqlQuery = "SELECT d_id, driver_name, email, driver_password, contact_number, 
                        created, date_of_birth, profile_pic_uri FROM " . $this->db_table . "";
            
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }

        // CREATE
        public function createDriver(){
            $emailQuery = "SELECT
                        u_id, 
                        email, 
                        user_password, 
                        user_type,
                        created
                      FROM
                        ". $this->db_table ."
                    WHERE 
                       email = :email";
            
            $stmt = $this->conn->prepare($emailQuery);
            $stmt->bindParam(":email", $this->email);
            $stmt->execute();     
            $user = $stmt->fetch();
            
            if ($user) {      
                $response = new Response();      
                $response->code = "100";
                $response->desc = "Email Already Exists";
                return $response;
            } else {
                $sqlQuery = "INSERT INTO 
                        ". $this->db_table ."
                    SET
                    email = :email, 
                    user_password = :user_password, 
                    user_type = :user_type, 
                    created = :created";
        
                $stmt = $this->conn->prepare($sqlQuery);

                // sanitize
                $this->email=htmlspecialchars(strip_tags($this->email));
                $this->password=htmlspecialchars(strip_tags($this->password));
                $this->userType=htmlspecialchars(strip_tags($this->userType));
                $this->created=htmlspecialchars(strip_tags($this->created));
            
                // bind data
                $stmt->bindParam(":email", $this->email);
                $stmt->bindParam(":user_password", $this->password);
                $stmt->bindParam(":user_type", $this->userType);
                $stmt->bindParam(":created", $this->created);
                
                if(!$stmt->execute()) {
                    $response = new Response();      
                    $response->code = "200";
                    $response->desc = "Error";
                    return $response;
                }

                $Query = "INSERT INTO 
                            ". $this->driver_table ."
                            SET
                            name = :name, 
                            email = :email, 
                            contact_number = :contact_number, 
                            profile_picture = :profile_picture,
                            nic_no = :nic_no,
                            date_of_birth = :date_of_birth";
                
                
                $stmt = $this->conn->prepare($Query);
                // sanitize
                $this->name=htmlspecialchars(strip_tags($this->name));
                $this->email=htmlspecialchars(strip_tags($this->email));
                $this->contactNumber=htmlspecialchars(strip_tags($this->contactNumber));
                $this->profilePicUri=htmlspecialchars(strip_tags($this->profilePicUri));
                $this->nic=htmlspecialchars(strip_tags($this->nic));
                $this->dateOfBirth=htmlspecialchars(strip_tags($this->dateOfBirth));
            
                // bind data
                $stmt->bindParam(":name", $this->name);
                $stmt->bindParam(":email", $this->email);
                $stmt->bindParam(":contact_number", $this->contactNumber);
                $stmt->bindParam(":date_of_birth", $this->dateOfBirth);
                $stmt->bindParam(":profile_picture", $this->profilePicUri);
                $stmt->bindParam(":nic_no", $this->nic);
            
                if($stmt->execute()){
                    $response = new Response();      
                    $response->code = "00";
                    $response->desc = "Driver registered successfully";
                    return $response;
                }else {
                    $response = new Response();      
                    $response->code = "100";
                    $response->desc = "Error";
                    return $response;
                }
            }
        }

        // UPDATE
        public function getSingleDriver(){
            $sqlQuery = "SELECT
                        u_id, 
                        drivername, 
                        email, 
                        driver_password, 
                        contact_number, 
                        created
                      FROM
                        ". $this->db_table ."
                    WHERE 
                       u_id = ?
                    LIMIT 0,1";

            $stmt = $this->conn->prepare($sqlQuery);

            $stmt->bindParam(1, $this->id);

            $stmt->execute();

            $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $this->name = $dataRow['drivername'];
            $this->email = $dataRow['email'];
            $this->password = $dataRow['driver_password'];
            $this->contactNumber = $dataRow['contact_number'];
            $this->created = $dataRow['created'];
        }        

        // UPDATE
        public function updateDriver(){
            $sqlQuery = "UPDATE
                        ". $this->db_table ."
                    SET
                        drivername = :name, 
                        email = :email, 
                        driver_password = :driver_password, 
                        contact_number = :contact_number, 
                        created = :created
                    WHERE 
                        u_id = :u_id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->password=htmlspecialchars(strip_tags($this->password));
            $this->contactNumber=htmlspecialchars(strip_tags($this->contactNumber));
            $this->created=htmlspecialchars(strip_tags($this->created));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind data
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":driver_password", $this->password);
            $stmt->bindParam(":contact_number", $this->contactNumber);
            $stmt->bindParam(":created", $this->created);
            $stmt->bindParam(":u_id", $this->id);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        // DELETE
        function deleteDriver(){
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE u_id = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            $stmt->bindParam(1, $this->id);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }

    }
?>

