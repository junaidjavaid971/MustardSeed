<?php
include_once '../models/ResponseModels/response.php';
include_once '../models/UserModels/ResetPasswordResponse.php';
require 'phpmailer/PHPMailerAutoload.php';

class User
{

    // Connection
    private $conn;

    // Table
    private $db_table = "user";
    private $profile_table = "user_profile";

    // Columns
    public $id;
    public $name;
    public $email;
    public $password;
    public $created;
    public $verified;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getUsers()
    {
        $sqlQuery = "SELECT u_id, u_name, u_email, u_password, created
             FROM " . $this->db_table . "";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createUser()
    {
        $existingUserQuery = "SELECT
                        *
                      FROM
                        " . $this->db_table . "
                    WHERE 
                    u_email = :u_email";

        $stmt = $this->conn->prepare($existingUserQuery);
        $stmt->bindParam(":u_email", $this->email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $response = new Response();
            $response->code = ResponseCodes::ACCOUNT_ALREADY_EXSISTS;
            $response->desc = "The user with this email already exists!";
            return $response;
        } else {
            $sqlQuery = "INSERT INTO 
                        " . $this->db_table . "
                    SET 
                    u_name = :u_name,
                    u_email = :u_email,
                    u_password = :u_password,
                    created = :created,
                    is_verified = :is_verified";

            $stmt = $this->conn->prepare($sqlQuery);

            // sanitize
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->created = htmlspecialchars(strip_tags($this->created));
            $this->verified = 0;
            // bind data
            $stmt->bindParam(":u_name", $this->name);
            $stmt->bindParam(":u_email", $this->email);
            $stmt->bindParam(":u_password", $this->password);
            $stmt->bindParam(":created", $this->created);
            $stmt->bindParam(":is_verified", $this->verified);

            if (!$stmt->execute()) {
                $response = new Response();
                $response->code = ResponseCodes::FAILURE;
                $response->desc = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                return $response;
            } else {
                $response = new Response();
                $response->code = ResponseCodes::SUCCESS;
                $response->desc = "Your account is created successfully. Please verify your email to continue.";
                return $response;
            }
        }
    }


    // CREATE
    public function saveGoogleUser()
    {
        $existingUserQuery = "SELECT
                            * FROM
                        " . $this->db_table . "
                    WHERE 
                    u_email = :u_email";

        $stmt = $this->conn->prepare($existingUserQuery);
        $stmt->bindParam(":u_email", $this->email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "The user with this email already exists!";
            return $response;
        } else {
            $sqlQuery = "INSERT INTO 
                        " . $this->db_table . "
                    SET 
                    u_name = :u_name, 
                    u_email = :u_email, 
                    u_password = :u_password,
                    created = :created.
                    verified =:verified";

            $stmt = $this->conn->prepare($sqlQuery);

            // sanitize
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->password = htmlspecialchars(strip_tags($this->password));
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->created = htmlspecialchars(strip_tags($this->created));

            // bind data
            $stmt->bindParam(":u_name", $this->name);
            $stmt->bindParam(":u_email", $this->email);
            $stmt->bindParam(":u_password", $this->password);
            $stmt->bindParam(":created", $this->created);
            $stmt->bindParam(":verified", $this->verified);

            if (!$stmt->execute()) {
                $response = new Response();
                $response->code = ResponseCodes::FAILURE;
                $response->desc = "Unexpected error occured while registring the user. Please try again!";
                return $response;
            } else {
                $response = new Response();
                $response->code = ResponseCodes::SUCCESS;
                $response->desc = "Your account is created successfully.";
                return $response;
            }
        }
    }

    // CREATE
    public function saveUserProfile()
    {
        $storeProfileQuery = "INSERT INTO 
                        " . $this->profile_table . "
                    SET 
                    email = :email, 
                    role = :role, 
                    contact_number = :contact_number,
                    address = :address,
                    country = :country,
                    state = :state,
                    zip_code = :zip_code,
                    accreditation = :accreditation,
                    qualification = :qualification,
                    college = :college,
                    passing_year = :passing_year,
                    supervisor_contact_number = :supervisor_contact_number,
                    supervisor_address = :supervisor_address,
                    supervisor_country = :supervisor_country,
                    supervisor_state = :supervisor_state,
                    supervisor_city = :supervisor_city,
                    supervisor_zip_code = :supervisor_zip_code,
                    is_client_supervisor = :is_client_supervisor,
                    terms_accepted = :terms_accepted";

        $stmt = $this->conn->prepare($storeProfileQuery);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->created = htmlspecialchars(strip_tags($this->created));

        // bind data
        $stmt->bindParam(":u_name", $this->name);
        $stmt->bindParam(":u_email", $this->email);
        $stmt->bindParam(":u_password", $this->password);
        $stmt->bindParam(":created", $this->created);

        if (!$stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Unexpected error occured while registring the user. Please try again!";
            return $response;
        } else {
            $this->sendVerificationEmail();
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Your account is created successfully. Please verify your email to continue.";
            return $response;
        }
    }

    // UPDATE
    public function getSingleUser()
    {
        $sqlQuery = "SELECT
                        u_id, 
                        username, 
                        email, 
                        user_password, 
                        contact_number, 
                        created
                      FROM
                        " . $this->db_table . "
                    WHERE 
                       u_id = ?
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = new UserModel();
        $user->name = $dataRow['username'];
        $user->email = $dataRow['email'];
        $user->password = $dataRow['user_password'];
        $user->contactNumber = $dataRow['contact_number'];
        $user->created = $dataRow['created'];
    }

    // UPDATE
    public function updateUser()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        username = :name, 
                        email = :email, 
                        user_password = :user_password, 
                        contact_number = :contact_number, 
                        created = :created
                    WHERE 
                        u_id = :u_id";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->contactNumber = htmlspecialchars(strip_tags($this->contactNumber));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind data
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":user_password", $this->password);
        $stmt->bindParam(":contact_number", $this->contactNumber);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":u_id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    function deleteUser()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE u_id = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function sendVerificationEmail()
    {
        $mail = new PHPMailer;

        $mail->isSMTP();
        //Set SMTP host name                          
        $mail->Host = "mail.gandi.net";
        //Set TCP port to connect to
        $mail->Port = 587;
        //Set this to true if SMTP host requires authentication to send email
        $mail->SMTPAuth = true;
        //If SMTP requires TLS encryption then set it
        $mail->SMTPSecure = "STARTTLS";
        //Provide username and password     
        $mail->Username = "noreply@mustardseed.care";
        $mail->Password = "MustardSeed@2021";
        $mail->setFrom('admin@mustardseedcic.co.uk', 'Mustard Seed');
        $mail->addAddress($this->email);
        $mail->isHTML(true);
        $randomNumber = mt_rand(1000, 9999);

        $mail->Subject = "Mustard Seed - Verify Your Account";

        if ($mail->send()) {
            return true;
        }
    }


    // UPDATE
    public function activateAccount()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        is_verified = :is_verified
                    WHERE 
                        u_email = :u_email";

        $stmt = $this->conn->prepare($sqlQuery);
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind data
        $stmt->bindParam(":u_email", $this->email);
        $stmt->bindParam(":is_verified", $this->verified);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
