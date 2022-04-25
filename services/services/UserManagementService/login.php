<?php

include_once '../models/ResponseModels/response.php';
include_once '../enums/responsecodes.php';
include_once '../enums/constants.php';
include_once '../services/UserManagementService/user.php';
include_once '../services/UserManagementService/driver.php';
include_once '../models/UserModels/usermodel.php';
include_once '../models/ResponseModels/generic.php';
class Login
{

    private $conn;
    private $db_table = "user";
    private $profile_table = "user_profile";
    private $session_table = "sessions";

    public $user_id;
    public $email;
    public $password;
    public $user;
    public $p;
    public $generic;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
        $this->user = new UserModel();
        $this->gen = new Generic();
    }

    public function login()
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->db_table . "
                    WHERE 
                       u_email = :u_email AND
                       u_password = :u_password
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);
        $verified = '1';
        $stmt->bindParam(":u_email", $this->email);
        $stmt->bindParam(":u_password", $this->password);

        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dataRow['u_name'] != "") {
            if ($dataRow['is_verified'] == 1) {
                $this->user->id = $dataRow['u_id'];
                $this->user->name = $dataRow['u_name'];
                $this->user->email = $dataRow['u_email'];
                $this->user->password = $dataRow['u_password'];
                $this->user->created = $dataRow['created'];
                
                $response = new Response();
                $response->code = ResponseCodes::SUCCESS;
                $response->desc = "Login Successful";
                $response->data = $this->checkProfile();
                return $response;
            } else {
                $response = new Response();
                $response->code = ResponseCodes::ACCOUNT_NOT_VERIFIED;
                $response->desc = "You're not allowed to login as your account is not verified yet.";
                return $response;
            }
        } else {
            $response = new Response();
            $response->code = ResponseCodes::INVALID_LOGIN_CREDENTIALS;
            $response->desc = "Your email or password is incorrect. Please try again by entering correct credentials.";
            return $response;
        }
    }

    private function checkProfile()
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->profile_table . "
                    WHERE 
                       email = :email
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":email", $this->email);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function logSession($u_id)
    {
        if ($this->isLoggedIn()) {
            return true;
        } else {
            $sqlQuery = "INSERT INTO 
                        " . $this->session_table . "
                    SET
                    user_id = :user_id, 
                    session_time = :session_time, 
                    is_session_active = :is_session_active";

            $stmt = $this->conn->prepare($sqlQuery);

            // sanitize
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));
            $date = date('Y-m-d H:i:s');
            $session_active = true;
            // bind data
            $stmt->bindParam(":user_id", $u_id);
            $stmt->bindParam(":session_time", $date);
            $stmt->bindParam(":is_session_active", $session_active);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }

    private function isLoggedIn()
    {
        $sqlQuery = "SELECT * FROM
                            " . $this->session_table . "
                        WHERE
                            user_id = :user_id AND
                            is_session_active = :is_session_active";

        $stmt = $this->conn->prepare($sqlQuery);

        $is_session_active = 1;
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":is_session_active", $this->is_session_active);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dataRow != null) {
            return true;
        } else {
            return false;
        }
    }

    public function closeAccount()
    {
        $sqlQuery = "SELECT
                        *
                      FROM
                        " . $this->db_table . "
                    WHERE 
                       u_email = :u_email AND
                       u_password = :u_password
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(":u_email", $this->email);
        $stmt->bindParam(":u_password", $this->password);

        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dataRow['u_name'] == "") {
            $response = new Response();
            $response->code = ResponseCodes::INVALID_LOGIN_CREDENTIALS;
            $response->desc = "We could not find an account with these credentials.";
            return $response;
        } else {
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE u_email = ?";
            $stmt = $this->conn->prepare($sqlQuery);

            $this->email = htmlspecialchars(strip_tags($this->email));

            $stmt->bindParam(1, $this->email);
            if ($stmt->execute()) {
                $response = new Response();
                $response->code = ResponseCodes::SUCCESS;
                $response->desc = "Account Deleted";
                return $response;
            }
        }
    }
}
