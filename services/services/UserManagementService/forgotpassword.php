<?php

include_once '../models/ResponseModels/response.php';
include_once '../models/UserModels/ResetPasswordResponse.php';
include_once '../enums/responsecodes.php';
require_once 'phpmailer/PHPMailerAutoload.php';
header('Content-Type:text/html; charset=UTF-8');

class ResetPassword
{

    // Connection
    private $conn;

    // Table
    private $db_table = "user";
    private $resetPasswordTable = "resetpasswordverificationcodes";
    private $regVerficiationCodeTable = "registrationverificationcodes";

    // Columns
    public $email;
    public $password;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function sendPasswordResetEmail()
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
        $mail->Password = "Q@werty@8102@";
        $mail->setFrom('noreply@mustardseed.care', 'Mustard Seed');
        $mail->addAddress($this->email);
        $mail->isHTML(true);
        $randomNumber = mt_rand(1000, 9999);

        $mail->Subject = "Mustard Seed - Reset Your Password";
        $mail->Body = "This is the automated email from Mustard Seed to reset the password, please enter the below given code in the app <br />
                            <p>Your Verification Code is: " . $randomNumber;
        if ($mail->send()) {
            $this->saveRandomNoInDB($randomNumber);
            $response = new ResetPasswordResponse();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Password Reset Email Sent Successfully!";
            $response->verificationCode = $randomNumber;
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Email Error: " . $mail->ErrorInfo;
            return $response;
        }
    }


    function sendSignupEmail($email, $name)
    {
        if (strcmp($name, "") == 0) {
            $name = $this->getUsername($email);
        }

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
        $mail->Password = "Q@werty@8102@";
        $mail->setFrom('noreply@mustardseed.care', 'Mustard Seed');
        $mail->addAddress($email);
        $randomNumber = mt_rand(1000, 9999);

        $mail->Subject = "Mustard Seed - Account Verification OTP";
        $url = file_get_contents("http://localhost/MustardSeed/registration-email.html");
        $content = str_replace(array('%name%', '%otp%'), array($name, $randomNumber), $url);

        $mail->Body = $content;
        $mail->isHTML(true);

        if ($mail->send()) {
            $this->saveVerificationCodeInDB($email, $randomNumber);
        }
    }


    private function getUsername()
    {
        $username = substr($this->email, 0, strrpos($this->email, '@'));
        return $username;
    }

    private function saveRandomNoInDB($code)
    {
        $sqlQuery = "INSERT INTO 
            " . $this->resetPasswordTable . "
                SET
                code = :code, 
                email = :email, 
                is_verified = :is_verified";

        $stmt = $this->conn->prepare($sqlQuery);

        $isVerfied = 0;
        // bind data
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":is_verified", $isVerfied);

        return ($stmt->execute() == true);
    }

    private function saveVerificationCodeInDB($email, $code)
    {
        $sqlQuery = "INSERT INTO 
            " . $this->regVerficiationCodeTable . "
                SET
                code = :code, 
                email = :email, 
                is_verified = :is_verified";

        $stmt = $this->conn->prepare($sqlQuery);

        $isVerfied = 0;
        // bind data
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":is_verified", $isVerfied);

        return ($stmt->execute() == true);
    }

    public function verifyResetPasswordCode($verCode)
    {

        $sqlQuery = "SELECT
                        *
                    FROM
                        " . $this->resetPasswordTable . "
                    WHERE 
                        email = :email AND
                        code = :code AND
                        is_verified = :verify
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);
        $verify = 0;
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":code", $verCode);
        $stmt->bindParam(":verify", $verify);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dataRow != null) {
            $sqlQuery = "UPDATE
                " . $this->resetPasswordTable . "
                SET 
                    is_verified = :is_verified
                WHERE 
                    email = :email AND
                    code = :code AND
                    is_verified = :verify";

            $stmt = $this->conn->prepare($sqlQuery);

            $isVerfied = 1;
            // bind data
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":code", $verCode);
            $stmt->bindParam(":is_verified", $isVerfied);
            $stmt->bindParam(":verify", $verify);

            if ($stmt->execute()) {
                $response = new Response();
                $response->code = ResponseCodes::SUCCESS;
                $response->desc = "Verification code verfied!";
                return $response;
            } else {
                $response = new Response();
                $response->code = ResponseCodes::INVALID_PASSWORD_RESET_VERIFICATION_CODE;
                $response->desc = "Verification code does not match";
                return $response;
            }
        } else {
            $response = new Response();
            $response->code = ResponseCodes::INVALID_PASSWORD_RESET_VERIFICATION_CODE;
            $response->desc = "Verification code does not match";
            return $response;
        }
    }

    public function changePassword($newPassword)
    {
        $sqlQuery = "UPDATE
                " . $this->db_table . "
                SET 
                    u_password = :u_password
                WHERE 
                    u_email = :u_email";

        $stmt = $this->conn->prepare($sqlQuery);

        //Bind Data
        $stmt->bindParam(":u_email", $this->email);
        $stmt->bindParam(":u_password", $newPassword);

        if ($stmt->execute()) {
            $response = new Response();
            $response->code = ResponseCodes::SUCCESS;
            $response->desc = "Password Updated Successfully!";
            return $response;
        } else {
            $response = new Response();
            $response->code = ResponseCodes::FAILURE;
            $response->desc = "Password Could not be updated";
            return $response;
        }
    }
}
