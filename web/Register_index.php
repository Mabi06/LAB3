<?php
// Start the PHP session and include the database connection file
session_start();
include "db_conn.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail($email, $v_code){

    require ("PHPMailer/PHPMailer.php");
    require ("PHPMailer/SMTP.php");
    require ("PHPMailer/Exception.php");

    $mail = new PHPMailer(true);

    try {
        //Server settings

        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'mangaronarch@gmail.com';                     //SMTP username
        $mail->Password   = 'atdw cugc zqnz iadm';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('mangaronarch@gmail.com', 'validation');
        $mail->addAddress($email);

    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email verification from mailer';
        $mail->Body    = "Thanks for registration! click the link to verify the email address
        <a href='http://localhost/web/success.php?email=$email&v_code=$v_code'>Verify</a>";
        
    
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}


// Check if form data is submitted
if (isset($_POST['fname']) && isset($_POST['mname']) && 
    isset($_POST['lname']) && isset($_POST['uname']) && 
    isset($_POST['email']) && isset($_POST['password']) && isset($_POST['repassword'])) {

    // Function to validate input data
    function validate($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Retrieve and validate form data
    $fname = validate($_POST['fname']);
    $mname = validate($_POST['mname']);
    $lname = validate($_POST['lname']);
    $uname = validate($_POST['uname']);
    $email = validate($_POST['email']);
    $pass = validate($_POST['password']);
    $repassword = validate($_POST['repassword']);
    $terms = ($_POST['terms']);

    //  Checking empty fields when error occurs the information still remains
    $user_data = "fname=" . $fname . "&mname=" . $mname . "&lname=" . $lname . "&uname=" . $uname. "&email=" . $email;

    // Check if required fields are empty and handle accordingly
    if (empty($fname)) {
        $em = "First name is required";
        header("Location: register.php?error=$em&$user_data");
        exit();
    }else if (empty($lname)){
        $em = "lastname is required";
        header("Location: register.php?error=$em&$user_data");
        exit();
    }else if (empty($uname)){
        $em = "Username is required";
        header("Location: register.php?error=$em&$user_data");
        exit();
    }else if (empty($email)){
        $em = "Email is required";
        header("Location: register.php?error=$em&$user_data");
        exit();
    }else if (empty($pass)){
        header("Location: register.php?error=Password is required&$user_data");
        exit();
    }else if (empty($repassword)){
        header("Location: register.php?error=Re password is required&$user_data");
        exit();
    }else if ($pass !== $repassword) {  
        header("Location: register.php?error=Confirmation password is not match&$user_data");
        exit();
    }else if (empty($terms)) {  
        header("Location: register.php?error=Please check the terms and conditions&$user_data");
        exit();
    }else {
        // Hash the password
        $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
        $v_code = bin2hex(random_bytes(16));

        // Check if the username already exists
        $sql = "SELECT * FROM user WHERE username='$uname'";
        $result = mysqli_query($conn, $sql);

        // If username exists, redirect with error message
        if (mysqli_num_rows($result) > 0) {
            header("Location: register.php?error=The username is already taken&$user_data");
            exit();
            }else{
                // Insert user data into the database
                $sql2 = "INSERT INTO user(First_name, Middle_name, Lastname, username ,Email, password, Verification_code, Is_verified) VALUES('$fname', '$mname', '$lname', '$uname', '$email', '$hashed_pass', '$v_code', '0')";
                $result2 = mysqli_query($conn, $sql2);

                // Redirect to success page or back to registration page with error message
            }if ($result2) {
                if (sendMail($_POST['email'], $v_code)) {
                    echo "<script> alert('Registration successful'); window.location.href='Loginform.php' </script>";
                    exit();
                } else {
                    echo "<script> alert('Email sending failed'); window.location.href='Loginform.php' </script>";
                    exit();
                }
            } else {
                echo "<script> alert('Server down or database error'); window.location.href='Loginform.php' </script>";
                exit();
            }
            
        }   
}else {
    // Redirect to the registration page if no form data is submitted
    header("Location: register.php");
    exit();
}