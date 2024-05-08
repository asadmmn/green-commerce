<?php

include 'includes/session.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['signup'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $registration_type = $_POST['registration_type'];

    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    $_SESSION['email'] = $email;

    if($password != $repassword){
        $_SESSION['error'] = 'Passwords did not match';
        header('location: signup.php');
        exit(); // Add exit to terminate script execution after redirect
    }
    else{
        $conn = $pdo->open();

        $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM users WHERE email=:email");
        $stmt->execute(['email'=>$email]);
        $row = $stmt->fetch();
        if($row['numrows'] > 0){
            $_SESSION['error'] = 'Email already taken';
            header('location: signup.php');
            exit(); // Add exit to terminate script execution after redirect
        }
        else{
            $now = date('Y-m-d');
            $password = password_hash($password, PASSWORD_DEFAULT);

            // Generate activation code
            $set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $code = substr(str_shuffle($set), 0, 12);

            try{
                $stmt = $conn->prepare("INSERT INTO users (email, password, firstname, lastname, activate_code, created_on, registration_type) VALUES (:email, :password, :firstname, :lastname, :code, :now, :registration_type)");
                $stmt->execute(['email'=>$email, 'password'=>$password, 'firstname'=>$firstname, 'lastname'=>$lastname, 'code'=>$code, 'now'=>$now, 'registration_type'=>$registration_type]);
                $userid = $conn->lastInsertId();

                // Handle additional fields based on registration type
                if($registration_type == 'resident'){
                    $location = $_POST['location'];
                    $age_group = $_POST['age_group'];
                    $gender = $_POST['gender'];
                    $areas_of_interest = $_POST['environmental_interest'];

                    $stmt = $conn->prepare("INSERT INTO residents (user_id, location, age_group, gender, areas_of_interest) VALUES (:user_id, :location, :age_group, :gender, :areas_of_interest)");
                    $stmt->execute(['user_id'=>$userid, 'location'=>$location, 'age_group'=>$age_group, 'gender'=>$gender, 'areas_of_interest'=>$areas_of_interest]);
                }
                elseif($registration_type == 'business'){
                    $company_name = $_POST['company_name'];
                    $contact_info = $_POST['contact_info'];
                    $eco_products_services = $_POST['eco_products_services'];

                    $stmt = $conn->prepare("INSERT INTO green_businesses (user_id, company_name, contact_info, eco_products_services) VALUES (:user_id, :company_name, :contact_info, :eco_products_services)");
                    $stmt->execute(['user_id'=>$userid, 'company_name'=>$company_name, 'contact_info'=>$contact_info, 'eco_products_services'=>$eco_products_services]);
                }

                // Get the absolute path to the current PHP file

                $currentFilePath = __FILE__;


                // Get the directory of the current PHP file

                $directoryPath = dirname($currentFilePath);

                $lastfolder= basename($directoryPath);

                // Get the filename of the current PHP file

                $fileName = basename($currentFilePath);

                $host = $_SERVER['HTTP_HOST'];

                echo "Host: $host";


                echo "Directory Path: $directoryPath<br>";

                echo "folder name:$lastfolder";

                echo "File Name: $fileName<br>";

                $message = "
                    <h2>Thank you for Registering.</h2>
                    <p>Your Account:</p>
                    <p>Email: ".$email."</p>
                    <p>Password: ".$_POST['password']."</p>
                    <p>Please click the link below to activate your account.</p>
                    <a href='http://".$host."/".$lastfolder."/activate.php?code=".$code."&user=".$userid."'>Activate Account</a>
                ";

                // Load PHPMailer
                require 'vendor/autoload.php';

                // Create a new PHPMailer instance
                $mail = new PHPMailer(true);

                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'muhammadsohailaslam50@gmail.com'; // Your Gmail address
                $mail->Password = 'dbhi kcbr eiha fhea'; // Your app password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Sender and recipient settings
                $mail->setFrom('asadm8975@gmail.com', 'Your Name'); // Replace with your name
                $mail->addAddress($email);
                $mail->addReplyTo('asadm8975@gmail.com', 'Your Name'); // Replace with your name

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Account Verification';
                $mail->Body = $message;

                // Send email
                if(!$mail->send()){
                    throw new Exception($mail->ErrorInfo);
                }

                // Clear session variables
                unset($_SESSION['firstname']);
                unset($_SESSION['lastname']);
                unset($_SESSION['email']);

                // Redirect with success message
                $_SESSION['success'] = 'Account created. Check your email to activate.';
                header('location: index.php');
                exit(); // Add exit to terminate script execution after redirect

            } catch(Exception $e){
                $_SESSION['error'] = 'Message could not be sent. Mailer Error: '.$e->getMessage();
                header('location: index.php');
                exit(); // Add exit to terminate script execution after redirect
            }

            $pdo->close();

        }

    }

}
else{
    $_SESSION['error'] = 'Fill up signup form first';
    header('location: signup.php');
    exit(); // Add exit to terminate script execution after redirect
}

?>
